<?php
/**
 * 帮助服务层
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/1 0001
 * Time: 下午 4:25
 */

namespace App\Services;


use App\Lib\Util\QueryPager;
use App\Model\ContractTenement;
use App\Model\FeeReceive;
use App\Model\Landlord;
use App\Model\LandlordOrder;
use App\Model\OrderArrears;
use App\Model\Providers;
use App\Model\Region;
use App\Model\RentArrears;
use App\Model\RentContact;
use App\Model\RentContract;
use App\Model\RentHouse;
use App\Model\RentPic;
use App\Model\SendMessage;
use App\Model\Verify;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Mpdf\Mpdf;
use setasign\Fpdi\PdfParser\StreamReader;

class NoteService extends CommonService
{
    /**
     * @description:欠款提示通知
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getArrearsNote(array $input)
    {
        $contract_id = $input['contract_id'];
        $arrears_fee = RentArrears::where('contract_id',$contract_id)->where('arrears_type','!=',4)->sum('need_pay_fee');
        $tenement_id = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_id')->first();
        $tenement_name = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_full_name')->first();
        $tenement_address = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_post_address')->first();
        $tenement_email = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_e_mail')->first();
        $tenement_phone = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_phone')->first();
        $landlord_name = RentContract::where('id',$contract_id)->pluck('landlord_full_name')->first();
        $landlord_mobile = RentContract::where('id',$contract_id)->pluck('landlord_mobile_phone')->first();
        $landlord_email = RentContract::where('id',$contract_id)->pluck('landlord_e_mail')->first();
        $date = date('Y-m-d');
        $content = "
                    $date
                    $tenement_name
                    $tenement_address
            Dear
            We have not received your payment of $ $arrears_fee Please arrange this payment to be paid as soon as you receive this message. 
            We might have no choice but to take further action if this matter can not be resloved soon. 
            If you already paid this, please ignore this message.
            We would be appreciated if you can make this payment as soon as possible.
            Please contact with us if you have any questions, thank you.  
            Form:
                    $landlord_name
                    $landlord_mobile
                    $landlord_email 
            Date:  	$date  
        ";

        $data = [
            'tenement_id'       => $tenement_id,
            'tenement_name'     => $tenement_name,
            'tenement_address'  => $tenement_address,
            'send_email'        => $tenement_email,
            'send_phone'        => $tenement_phone,
            'content'           => $content,
            'landlord_name'     => $landlord_name,
            'landlord_mobile'   => $landlord_mobile,
            'landlord_email'    => $landlord_email,
        ];
        return $this->success('get message success',$data);
    }



    /**
     * @description:欠款14天提示通知
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFourteenDaysArrearsNote(array $input)
    {
        $contract_id = $input['contract_id'];
        $arrears_fee = RentArrears::where('contract_id',$contract_id)->where('arrears_type','!=',4)->sum('need_pay_fee');
        $arrears_ids = RentArrears::where('contract_id',$contract_id)->where('arrears_type','!=',4)->pluck('id');
        $last_pay_fee = FeeReceive::whereIn('arrears_id',$arrears_ids)->orderByDesc('id')->pluck('pay_money')->first();
        $last_pay_date = FeeReceive::whereIn('arrears_id',$arrears_ids)->orderByDesc('id')->pluck('created_at')->first();
        $fourteen_days = date('Y-m-d',strtotime('+ 14 days'));
        $next_day = date('Y-m-d',strtotime('+ 7 days'));
        $tenement_id = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_id')->first();
        $tenement_name = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_full_name')->first();
        $tenement_address = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_post_address')->first();
        $tenement_email = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_e_mail')->first();
        $tenement_phone = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_phone')->first();
        $landlord_name = RentContract::where('id',$contract_id)->pluck('landlord_full_name')->first();
        $landlord_mobile = RentContract::where('id',$contract_id)->pluck('landlord_mobile_phone')->first();
        $landlord_email = RentContract::where('id',$contract_id)->pluck('landlord_e_mail')->first();
        $date = date('Y-m-d');
        $content = "$date
                    $tenement_name
                    $tenement_address
                    Dear 
                        Tenancy at: 
                        This is not an eviction notice.  This is a 14-day Notice to Remedy regarding rent arrears.
                        Your rent is behind by $ $arrears_fee  This is a breach of the Residential Tenancies Act 1986 and our tenancy agreement.  
                        The last payment received was $ $last_pay_fee on $last_pay_date  You are required by law to pay rent when it is due.
                        Please pay $ $arrears_fee by $fourteen_days (at least 14 days from but not including today*) (the Payment Date.  
                        You will also need to pay your current rent due on $next_day to bring your rent payments up to date. 
                        Please call me on $landlord_mobile or email $landlord_email to discuss arrangements for you to pay the missed rent.
                        If you do not make this payment on or before the Payment Date, or make an arrangement with me to pay, I can apply to the Tenancy Tribunal to end your tenancy, and for you to pay all the rent owed.
                        I enclose a copy of your rent record for you to check with your bank statements or receipts.
                        Yours sincerely
                    Form:
                    $landlord_name
                    $landlord_mobile
                    $landlord_email   
                     Delivery:
                    Date:  	$date
                    By (tick):
                    mail (*allow 4 extra working days from but not including today)
                    hand into letterbox (*allow 2 extra working days from but not including today)
                    email to an email address given as an additional address for service
                    (*if sent by email after 5pm, allow 1 extra working day from but not including today)
                    fax to a facsimile number given as an additional address for service
                    (*if sent by fax after 5pm, allow 1 extra working day from but not including today)
                    hand to tenant
                    ";

        $data = [
            'tenement_id'       => $tenement_id,
            'tenement_name'     => $tenement_name,
            'tenement_address'  => $tenement_address,
            'send_email'        => $tenement_email,
            'send_phone'        => $tenement_phone,
            'content'           => $content,
            'landlord_name'     => $landlord_name,
            'landlord_mobile'   => $landlord_mobile,
            'landlord_email'    => $landlord_email,
        ];
        return $this->success('get message success',$data);
    }


    /**
     * @description:欠款警告通知
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getArrearsWarning(array $input)
    {
        $contract_id = $input['contract_id'];
        $arrears_fee = RentArrears::where('contract_id',$contract_id)->where('arrears_type','!=',4)->sum('need_pay_fee');
        $tenement_id = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_id')->first();
        $tenement_name = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_full_name')->first();
        $tenement_address = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_post_address')->first();
        $tenement_email = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_e_mail')->first();
        $tenement_phone = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_phone')->first();
        $landlord_name = RentContract::where('id',$contract_id)->pluck('landlord_full_name')->first();
        $landlord_mobile = RentContract::where('id',$contract_id)->pluck('landlord_mobile_phone')->first();
        $landlord_email = RentContract::where('id',$contract_id)->pluck('landlord_e_mail')->first();
        $date = date('Y-m-d');
        $content = "
         $date
                    $tenement_name
                    $tenement_address
                Dear
        We have not received your payment of $ $arrears_fee . Please arrange this payment to be paid as soon as you receive this message. 
        We might have no choice but to take further action if this matter can not be resloved soon. 
        If you already paid this, please ignore this message.
        We would be appreciated if you can make this payment as soon as possible.
        Please contact with us if you have any questions, thank you. 
        Form:
                    $landlord_name
                    $landlord_mobile
                    $landlord_email 
            Date:  	$date    
        ";
        $data = [
            'tenement_id'       => $tenement_id,
            'tenement_name'     => $tenement_name,
            'tenement_address'  => $tenement_address,
            'send_email'        => $tenement_email,
            'send_phone'        => $tenement_phone,
            'content'           => $content,
            'landlord_name'     => $landlord_name,
            'landlord_mobile'   => $landlord_mobile,
            'landlord_email'    => $landlord_email,
        ];
        return $this->success('get message success',$data);
    }

    /**
     * @description:联系房东通知
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function contactLandLord(array $input)
    {
        $contract_id = $input['contract_id'];
        $tenement_id = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_id')->first();
        $tenement_name = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_full_name')->first();
        $tenement_address = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_post_address')->first();
        $tenement_email = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_e_mail')->first();
        $tenement_phone = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_phone')->first();
        $landlord_hm = RentContract::where('contract_id',$contract_id)->pluck('landlord_hm')->first();
        $landlord_wk = RentContract::where('contract_id',$contract_id)->pluck('landlord_wk')->first();
        $landlord_name = RentContract::where('id',$contract_id)->pluck('landlord_full_name')->first();
        $landlord_mobile = RentContract::where('id',$contract_id)->pluck('landlord_mobile_phone')->first();
        $landlord_email = RentContract::where('id',$contract_id)->pluck('landlord_e_mail')->first();
        $date = date('Y-m-d');
        $content = "
         $date
                    $tenement_address
                     $tenement_name
            Contact details
            Please contact me at the details below if any urgent repairs are needed.
            Landlord contact details
            Home phone: $landlord_hm
            Work phone: $landlord_wk
            Mobile phone: $landlord_mobile
            Email: $landlord_email
            Alternative contact details (partner, friend, relative)
            Name:
            Home phone:
            Work phone:
            Mobile phone:
            Email:
            If you are not able to contact me, or the alternative contact person, and the repairs are urgent, please contact one of the following tradespeople:
            Tradespeople contact details
            Plumber name:	Phone:
            Electrician name:	Phone:
            Glazier name:	Phone:
            Kind regards
            $landlord_name
        ";
        $data = [
            'tenement_id'       => $tenement_id,
            'tenement_name'     => $tenement_name,
            'tenement_address'  => $tenement_address,
            'send_email'        => $tenement_email,
            'send_phone'        => $tenement_phone,
            'content'           => $content,
            'landlord_name'     => $landlord_name,
            'landlord_mobile'   => $landlord_mobile,
            'landlord_email'    => $landlord_email,
        ];
        return $this->success('get message success',$data);
    }


    /**
     * @description:固定租约到期不续约通知
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function contactNotSignAgain(array $input)
    {
        $contract_id = $input['contract_id'];
        $tenement_id = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_id')->first();
        $tenement_name = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_full_name')->first();
        $tenement_address = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_post_address')->first();
        $tenement_email = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_e_mail')->first();
        $tenement_phone = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_phone')->first();
        $landlord_hm = RentContract::where('contract_id',$contract_id)->pluck('landlord_hm')->first();
        $landlord_wk = RentContract::where('contract_id',$contract_id)->pluck('landlord_wk')->first();
        $landlord_name = RentContract::where('id',$contract_id)->pluck('landlord_full_name')->first();
        $landlord_mobile = RentContract::where('id',$contract_id)->pluck('landlord_mobile_phone')->first();
        $landlord_email = RentContract::where('id',$contract_id)->pluck('landlord_e_mail')->first();
        $rent_house_id = RentContract::where('id',$contract_id)->pluck('house_id')->first();
        $house_address = RentHouse::where('id',$rent_house_id)->pluck('address')->first();
        $end_time = RentContract::where('id',$contract_id)->pluck('rent_end_date')->first();
        $date = date('Y-m-d');
        $content = "
         $date
          $tenement_name
                    $tenement_address   
                    Dear  $tenement_name
                    Tenancy at: $house_address
          This letter serves as notice that the fixed-term tenancy at the above address will expire on $end_time and will not be continued past this date.
            The Residential Tenancies Act 1986 requires this notice to be served not more than 90 days and not less than 21 days before the date on which the tenancy expires.
Please contact me if you have any questions.
            Home phone: $landlord_hm
            Work phone: $landlord_wk
            Mobile phone: $landlord_mobile
            Email: $landlord_email
              Yours sincerely
                    Form:
                    $landlord_name
                    $landlord_mobile
                    $landlord_email   
                     Delivery:
                    Date:  	$date
                    By (tick):
                   mail (*allow 4 extra working days)
                   placed into letterbox or attached to the door (* allow 2 extra working days)
                    (*if sent by email after 5pm, allow 1 extra working day from but not including today)
                    Sent via email or faxed to tenant after 5pm (*allow 1 extra working day)
                    hand to tenant, sent via email or faxed before 5pm on the date of the notice 
(the first day of the notice period will be the next calendar day)
        ";
        $data = [
            'tenement_id'       => $tenement_id,
            'tenement_name'     => $tenement_name,
            'tenement_address'  => $tenement_address,
            'send_email'        => $tenement_email,
            'send_phone'        => $tenement_phone,
            'content'           => $content,
            'landlord_name'     => $landlord_name,
            'landlord_mobile'   => $landlord_mobile,
            'landlord_email'    => $landlord_email,
        ];
        return $this->success('get message success',$data);
    }



    /**
     * @description:分租涨租通知
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function subletLeaseUp(array $input)
    {
        $contract_id = $input['contract_id'];
        $tenement_id = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_id')->first();
        $tenement_name = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_full_name')->first();
        $tenement_address = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_post_address')->first();
        $tenement_email = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_e_mail')->first();
        $tenement_phone = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_phone')->first();
        $landlord_hm = RentContract::where('contract_id',$contract_id)->pluck('landlord_hm')->first();
        $landlord_wk = RentContract::where('contract_id',$contract_id)->pluck('landlord_wk')->first();
        $landlord_name = RentContract::where('id',$contract_id)->pluck('landlord_full_name')->first();
        $landlord_mobile = RentContract::where('id',$contract_id)->pluck('landlord_mobile_phone')->first();
        $landlord_email = RentContract::where('id',$contract_id)->pluck('landlord_e_mail')->first();
        $rent_house_id = RentContract::where('id',$contract_id)->pluck('house_id')->first();
        $house_address = RentHouse::where('id',$rent_house_id)->pluck('address')->first();
        $date = date('Y-m-d');
        $content = "
         $date
          $tenement_name
                    $tenement_address   
                    Dear  $tenement_name
                    Tenancy at: $house_address
         This letter serves as a notice of rent increase for the tenancy at the above address.

The new weekly rental will be  and will be payable from 

The Residential Tenancies Act 1986 requires me to give you not less than 28 days notice of a rent increase.

Please contact me if you have any questions.
            Home phone: $landlord_hm
            Work phone: $landlord_wk
            Mobile phone: $landlord_mobile
            Email: $landlord_email
              Yours sincerely
                  
                    $landlord_name
                 
                     Delivery:
                    Date:  	$date
                    By (tick):
                   mail (*allow 4 extra working days)
                   placed into letterbox or attached to the door (* allow 2 extra working days)
                    (*if sent by email after 5pm, allow 1 extra working day from but not including today)
                    Sent via email or faxed to tenant after 5pm (*allow 1 extra working day)
                    hand to tenant, sent via email or faxed before 5pm on the date of the notice 
(the first day of the notice period will be the next calendar day)
        ";
        $data = [
            'tenement_id'       => $tenement_id,
            'tenement_name'     => $tenement_name,
            'tenement_address'  => $tenement_address,
            'send_email'        => $tenement_email,
            'send_phone'        => $tenement_phone,
            'content'           => $content,
            'landlord_name'     => $landlord_name,
            'landlord_mobile'   => $landlord_mobile,
            'landlord_email'    => $landlord_email,
        ];
        return $this->success('get message success',$data);
    }


    /**
     * @description:涨租通知
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function leaseUp(array $input)
    {
        $contract_id = $input['contract_id'];
        $tenement_id = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_id')->first();
        $tenement_name = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_full_name')->first();
        $tenement_address = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_post_address')->first();
        $tenement_email = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_e_mail')->first();
        $tenement_phone = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_phone')->first();
        $landlord_hm = RentContract::where('contract_id',$contract_id)->pluck('landlord_hm')->first();
        $landlord_wk = RentContract::where('contract_id',$contract_id)->pluck('landlord_wk')->first();
        $landlord_name = RentContract::where('id',$contract_id)->pluck('landlord_full_name')->first();
        $landlord_mobile = RentContract::where('id',$contract_id)->pluck('landlord_mobile_phone')->first();
        $landlord_email = RentContract::where('id',$contract_id)->pluck('landlord_e_mail')->first();
        $rent_house_id = RentContract::where('id',$contract_id)->pluck('house_id')->first();
        $house_address = RentHouse::where('id',$rent_house_id)->pluck('address')->first();
        $date = date('Y-m-d');
        $content = "
         $date
          $tenement_name
                    $tenement_address   
                    Dear  $tenement_name
                    Tenancy at: $house_address
         This letter serves as a notice of rent increase for the tenancy at the above address.

The new weekly rental will be  and will be payable from 

The Residential Tenancies Act 1986 requires me to give you not less than 28 days notice of a rent increase.

Please contact me if you have any questions.
            Home phone: $landlord_hm
            Work phone: $landlord_wk
            Mobile phone: $landlord_mobile
            Email: $landlord_email
              Yours sincerely
                  
                    $landlord_name
                 
                     Delivery:
                    Date:  	$date
                    By (tick):
                   mail (*allow 4 extra working days)
                   placed into letterbox or attached to the door (* allow 2 extra working days)
                    (*if sent by email after 5pm, allow 1 extra working day from but not including today)
                    Sent via email or faxed to tenant after 5pm (*allow 1 extra working day)
                    hand to tenant, sent via email or faxed before 5pm on the date of the notice 
(the first day of the notice period will be the next calendar day)
        ";
        $data = [
            'tenement_id'       => $tenement_id,
            'tenement_name'     => $tenement_name,
            'tenement_address'  => $tenement_address,
            'send_email'        => $tenement_email,
            'send_phone'        => $tenement_phone,
            'content'           => $content,
            'landlord_name'     => $landlord_name,
            'landlord_mobile'   => $landlord_mobile,
            'landlord_email'    => $landlord_email,
        ];
        return $this->success('get message success',$data);
    }

    /**
     * @description:房东搬入通知
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function landlordMoveIn(array $input)
    {
        $contract_id = $input['contract_id'];
        $tenement_id = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_id')->first();
        $tenement_name = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_full_name')->first();
        $tenement_address = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_post_address')->first();
        $tenement_email = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_e_mail')->first();
        $tenement_phone = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_phone')->first();
        $landlord_hm = RentContract::where('contract_id',$contract_id)->pluck('landlord_hm')->first();
        $landlord_wk = RentContract::where('contract_id',$contract_id)->pluck('landlord_wk')->first();
        $landlord_name = RentContract::where('id',$contract_id)->pluck('landlord_full_name')->first();
        $landlord_mobile = RentContract::where('id',$contract_id)->pluck('landlord_mobile_phone')->first();
        $landlord_email = RentContract::where('id',$contract_id)->pluck('landlord_e_mail')->first();
        $rent_house_id = RentContract::where('id',$contract_id)->pluck('house_id')->first();
        $house_address = RentHouse::where('id',$rent_house_id)->pluck('address')->first();
        $date = date('Y-m-d');
        $fourtytwo_date = date('Y-m-d',strtotime('+ 42 days'));
        $content = "
         $date
          $tenement_name
                    $tenement_address   
                    Dear  $tenement_name
                    Tenancy at: $house_address
        This letter serves as notice to terminate the periodic tenancy agreement at the above address.

The last day of the tenancy will be $fourtytwo_date

The Residential Tenancies Act 1986 requires me to give you not less than 42 days notice to terminate the agreement.

As clearly stated on the Tenancy Agreement, these premises are customarily used for occupation by employees of the landlord, and the premises are now required for this purpose.

Please contact me if you have any questions.
            Home phone: $landlord_hm
            Work phone: $landlord_wk
            Mobile phone: $landlord_mobile
            Email: $landlord_email
              Yours sincerely
                  
                    $landlord_name
                 
                     Delivery:
                    Date:  	$date
                    By (tick):
                   mail (*allow 4 extra working days)
                   placed into letterbox or attached to the door (* allow 2 extra working days)
                    (*if sent by email after 5pm, allow 1 extra working day from but not including today)
                    Sent via email or faxed to tenant after 5pm (*allow 1 extra working day)
                    hand to tenant, sent via email or faxed before 5pm on the date of the notice 
(the first day of the notice period will be the next calendar day)
        ";
        $data = [
            'tenement_id'       => $tenement_id,
            'tenement_name'     => $tenement_name,
            'tenement_address'  => $tenement_address,
            'send_email'        => $tenement_email,
            'send_phone'        => $tenement_phone,
            'content'           => $content,
            'landlord_name'     => $landlord_name,
            'landlord_mobile'   => $landlord_mobile,
            'landlord_email'    => $landlord_email,
        ];
        return $this->success('get message success',$data);
    }


    /**
     * @description:开放式合约结束租约
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function stopRent(array $input)
    {
        $contract_id = $input['contract_id'];
        $tenement_id = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_id')->first();
        $tenement_name = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_full_name')->first();
        $tenement_address = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_post_address')->first();
        $tenement_email = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_e_mail')->first();
        $tenement_phone = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_phone')->first();
        $landlord_hm = RentContract::where('contract_id',$contract_id)->pluck('landlord_hm')->first();
        $landlord_wk = RentContract::where('contract_id',$contract_id)->pluck('landlord_wk')->first();
        $landlord_name = RentContract::where('id',$contract_id)->pluck('landlord_full_name')->first();
        $landlord_mobile = RentContract::where('id',$contract_id)->pluck('landlord_mobile_phone')->first();
        $landlord_email = RentContract::where('id',$contract_id)->pluck('landlord_e_mail')->first();
        $rent_house_id = RentContract::where('id',$contract_id)->pluck('house_id')->first();
        $house_address = RentHouse::where('id',$rent_house_id)->pluck('address')->first();
        $date = date('Y-m-d');
        $ninety_date = date('Y-m-d',strtotime('+ 90 days'));
        $content = "
         $date
          $tenement_name
                    $tenement_address   
                    Dear  $tenement_name
                    Tenancy at: $house_address
        This letter serves as notice to terminate the periodic tenancy agreement at the above address.

The last day of the tenancy will be $ninety_date

The Residential Tenancies Act 1986 requires me to give you not less than 90 days notice to terminate the agreement.

Please contact me if you have any questions.
            Home phone: $landlord_hm
            Work phone: $landlord_wk
            Mobile phone: $landlord_mobile
            Email: $landlord_email
              Yours sincerely
                  
                    $landlord_name
                 
                     Delivery:
                    Date:  	$date
                    By (tick):
                   mail (*allow 4 extra working days)
                   placed into letterbox or attached to the door (* allow 2 extra working days)
                    (*if sent by email after 5pm, allow 1 extra working day from but not including today)
                    Sent via email or faxed to tenant after 5pm (*allow 1 extra working day)
                    hand to tenant, sent via email or faxed before 5pm on the date of the notice 
(the first day of the notice period will be the next calendar day)
        ";
        $data = [
            'tenement_id'       => $tenement_id,
            'tenement_name'     => $tenement_name,
            'tenement_address'  => $tenement_address,
            'send_email'        => $tenement_email,
            'send_phone'        => $tenement_phone,
            'content'           => $content,
            'landlord_name'     => $landlord_name,
            'landlord_mobile'   => $landlord_mobile,
            'landlord_email'    => $landlord_email,
        ];
        return $this->success('get message success',$data);
    }


    /**
     * @description:家庭成员搬回
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function homeIn(array $input)
    {
        $contract_id = $input['contract_id'];
        $tenement_id = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_id')->first();
        $tenement_name = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_full_name')->first();
        $tenement_address = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_post_address')->first();
        $tenement_email = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_e_mail')->first();
        $tenement_phone = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_phone')->first();
        $landlord_hm = RentContract::where('contract_id',$contract_id)->pluck('landlord_hm')->first();
        $landlord_wk = RentContract::where('contract_id',$contract_id)->pluck('landlord_wk')->first();
        $landlord_name = RentContract::where('id',$contract_id)->pluck('landlord_full_name')->first();
        $landlord_mobile = RentContract::where('id',$contract_id)->pluck('landlord_mobile_phone')->first();
        $landlord_email = RentContract::where('id',$contract_id)->pluck('landlord_e_mail')->first();
        $rent_house_id = RentContract::where('id',$contract_id)->pluck('house_id')->first();
        $house_address = RentHouse::where('id',$rent_house_id)->pluck('address')->first();
        $date = date('Y-m-d');
        $fourtytwo_date = date('Y-m-d',strtotime('+ 42 days'));
        $content = "
         $date
          $tenement_name
                    $tenement_address   
                    Dear  $tenement_name
                    Tenancy at: $house_address
        This letter serves as notice to terminate the periodic tenancy agreement at the above address.

The last day of the tenancy will be $fourtytwo_date

The Residential Tenancies Act 1986 requires me to give you not less than 42 days notice to terminate the agreement.

The premises are required as the principal place of residence for the owner or a member of the owner’s family.

Please contact me if you have any questions.
            Home phone: $landlord_hm
            Work phone: $landlord_wk
            Mobile phone: $landlord_mobile
            Email: $landlord_email
              Yours sincerely
                  
                    $landlord_name
                 
                     Delivery:
                    Date:  	$date
                    By (tick):
                   mail (*allow 4 extra working days)
                   placed into letterbox or attached to the door (* allow 2 extra working days)
                    (*if sent by email after 5pm, allow 1 extra working day from but not including today)
                    Sent via email or faxed to tenant after 5pm (*allow 1 extra working day)
                    hand to tenant, sent via email or faxed before 5pm on the date of the notice 
(the first day of the notice period will be the next calendar day)
        ";
        $data = [
            'tenement_id'       => $tenement_id,
            'tenement_name'     => $tenement_name,
            'tenement_address'  => $tenement_address,
            'send_email'        => $tenement_email,
            'send_phone'        => $tenement_phone,
            'content'           => $content,
            'landlord_name'     => $landlord_name,
            'landlord_mobile'   => $landlord_mobile,
            'landlord_email'    => $landlord_email,
        ];
        return $this->success('get message success',$data);
    }


    /**
     * @description:房东卖房
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function saleHouse(array $input)
    {
        $contract_id = $input['contract_id'];
        $tenement_id = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_id')->first();
        $tenement_name = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_full_name')->first();
        $tenement_address = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_post_address')->first();
        $tenement_email = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_e_mail')->first();
        $tenement_phone = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_phone')->first();
        $landlord_hm = RentContract::where('contract_id',$contract_id)->pluck('landlord_hm')->first();
        $landlord_wk = RentContract::where('contract_id',$contract_id)->pluck('landlord_wk')->first();
        $landlord_name = RentContract::where('id',$contract_id)->pluck('landlord_full_name')->first();
        $landlord_mobile = RentContract::where('id',$contract_id)->pluck('landlord_mobile_phone')->first();
        $landlord_email = RentContract::where('id',$contract_id)->pluck('landlord_e_mail')->first();
        $rent_house_id = RentContract::where('id',$contract_id)->pluck('house_id')->first();
        $house_address = RentHouse::where('id',$rent_house_id)->pluck('address')->first();
        $date = date('Y-m-d');
        $fourtytwo_date = date('Y-m-d',strtotime('+ 42 days'));
        $content = "
         $date
          $tenement_name
                    $tenement_address   
                    Dear  $tenement_name
                    Tenancy at: $house_address
       This letter serves as notice to terminate the periodic tenancy agreement at the above address.

The last day of the tenancy will be $fourtytwo_date

The Residential Tenancies Act 1986 requires me to give you not less than 42 days notice to terminate the agreement.

The owner is required, having entered into an unconditional agreement for the sale of the premises, to give the purchaser vacant possession of the premises.
            Home phone: $landlord_hm
            Work phone: $landlord_wk
            Mobile phone: $landlord_mobile
            Email: $landlord_email
              Yours sincerely
                  
                    $landlord_name
                 
                     Delivery:
                    Date:  	$date
                    By (tick):
                   mail (*allow 4 extra working days)
                   placed into letterbox or attached to the door (* allow 2 extra working days)
                    (*if sent by email after 5pm, allow 1 extra working day from but not including today)
                    Sent via email or faxed to tenant after 5pm (*allow 1 extra working day)
                    hand to tenant, sent via email or faxed before 5pm on the date of the notice 
(the first day of the notice period will be the next calendar day)
        ";
        $data = [
            'tenement_id'       => $tenement_id,
            'tenement_name'     => $tenement_name,
            'tenement_address'  => $tenement_address,
            'send_email'        => $tenement_email,
            'send_phone'        => $tenement_phone,
            'content'           => $content,
            'landlord_name'     => $landlord_name,
            'landlord_mobile'   => $landlord_mobile,
            'landlord_email'    => $landlord_email,
        ];
        return $this->success('get message success',$data);
    }

    /**
     * @description:14天租客违约警告
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function fourteenDaysNote(array $input)
    {
        $contract_id = $input['contract_id'];
        $tenement_id = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_id')->first();
        $tenement_name = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_full_name')->first();
        $tenement_address = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_post_address')->first();
        $tenement_email = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_e_mail')->first();
        $tenement_phone = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_phone')->first();
        $landlord_hm = RentContract::where('contract_id',$contract_id)->pluck('landlord_hm')->first();
        $landlord_wk = RentContract::where('contract_id',$contract_id)->pluck('landlord_wk')->first();
        $landlord_name = RentContract::where('id',$contract_id)->pluck('landlord_full_name')->first();
        $landlord_mobile = RentContract::where('id',$contract_id)->pluck('landlord_mobile_phone')->first();
        $landlord_email = RentContract::where('id',$contract_id)->pluck('landlord_e_mail')->first();
        $rent_house_id = RentContract::where('id',$contract_id)->pluck('house_id')->first();
        $house_address = RentHouse::where('id',$rent_house_id)->pluck('address')->first();
        $date = date('Y-m-d');
        $fourteen_days = date('Y-m-d',strtotime('+ 14 days'));
        $content = "
         $date
          $tenement_name
                    $tenement_address   
                    Dear  $tenement_name
                    Tenancy at: $house_address
       I am writing to let you know that you have not kept to your tenancy responsibilities by:
 ................................................................................................................................................................
 ................................................................................................................................................................
 ................................................................................................................................................................
This letter is not an eviction notice.  It is a notice giving you until $fourteen_days  (at least 14 days from but not including today*) (the Remedy Date) to remedy the situation by doing the following:
 ................................................................................................................................................................
 ................................................................................................................................................................
 ................................................................................................................................................................

I can apply to the Tenancy Tribunal to end your tenancy if this is not remedied on or before the Remedy Date.
            Home phone: $landlord_hm
            Work phone: $landlord_wk
            Mobile phone: $landlord_mobile
            Email: $landlord_email
              Yours sincerely
                  
                    $landlord_name
                 
                     Delivery:
                    Date:  	$date
                    By (tick):
                   mail (*allow 4 extra working days)
                   placed into letterbox or attached to the door (* allow 2 extra working days)
                    (*if sent by email after 5pm, allow 1 extra working day from but not including today)
                    Sent via email or faxed to tenant after 5pm (*allow 1 extra working day)
                    hand to tenant, sent via email or faxed before 5pm on the date of the notice 
(the first day of the notice period will be the next calendar day)
        ";
        $data = [
            'tenement_id'       => $tenement_id,
            'tenement_name'     => $tenement_name,
            'tenement_address'  => $tenement_address,
            'send_email'        => $tenement_email,
            'send_phone'        => $tenement_phone,
            'content'           => $content,
            'landlord_name'     => $landlord_name,
            'landlord_mobile'   => $landlord_mobile,
            'landlord_email'    => $landlord_email,
        ];
        return $this->success('get message success',$data);
    }


    /**
     * @description:发票通知
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function invoiceNote(array $input)
    {
        $order_id = $input['order_id'];
        $landlord_user_id = LandlordOrder::where('id',$order_id)->pluck('user_id')->first();
        $landlord_email = Landlord::where('user_id',$landlord_user_id)->pluck('email')->first();
        $landlord_phone = Landlord::where('user_id',$landlord_user_id)->pluck('phone')->first();
        $providers_id = LandlordOrder::where('id',$order_id)->pluck('providers_id')->first();
        $providers_email = Providers::where('id',$providers_id)->pluck('email')->first();
        $providers_company_name = Providers::where('id',$providers_id)->pluck('service_name')->first();
        $providers_name = Providers::where('id',$providers_id)->pluck('first_name')->first();
        $providers_phone = Providers::where('id',$providers_id)->pluck('phone')->first();
        $content = "
         We have send you an invoice to $landlord_email please check it and arrange the payment. thank you. 

From: $providers_name $providers_company_name
$providers_phone
$providers_email
        ";
        $data = [
            'landlord_user_id'  => $landlord_user_id,
            'content'           => $content,
            'send_email'        => $landlord_email,
            'send_phone'        => $landlord_phone,
        ];
        return $this->success('get message success',$data);
    }


    /**
     * @description:房东欠款
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function landlordArrearsNote(array $input)
    {
        $order_id = $input['order_id'];
        $landlord_user_id = LandlordOrder::where('id',$order_id)->pluck('user_id')->first();
        $landlord_name = Landlord::where('user_id',$landlord_user_id)->pluck('landlord_name')->first();
        $landlord_email = Landlord::where('user_id',$landlord_user_id)->pluck('email')->first();
        $landlord_address = Landlord::where('user_id',$landlord_user_id)->pluck('address')->first();
        $landlord_phone = Landlord::where('user_id',$landlord_user_id)->pluck('phone')->first();
        $arrears_fee = OrderArrears::where('order_id',$order_id)->sum('need_pay_fee');
        $providers_id = LandlordOrder::where('id',$order_id)->pluck('providers_id')->first();
        $providers_email = Providers::where('id',$providers_id)->pluck('email')->first();
        $providers_company_name = Providers::where('id',$providers_id)->pluck('service_name')->first();
        $providers_name = Providers::where('id',$providers_id)->pluck('first_name')->first();
        $providers_phone = Providers::where('id',$providers_id)->pluck('phone')->first();
        $date = date('Y-m-d');
        $content = "
                    $date
                    $landlord_name
                    $landlord_address
            Dear
        This is a reminder that we have not received your payment of $ $arrears_fee . If you already paid this, please ignore this message.

We would be appreciated if you can make this payment as soon as possible.  Please contact with us if you have any questions, thank you. 
            Form:
                    $providers_name
                    $providers_phone
                    $providers_email 
            Date:  	$date  
        ";

        $data = [
            'landlord_user_id'  => $landlord_user_id,
            'content'           => $content,
            'send_email'        => $landlord_email,
            'send_phone'        => $landlord_phone,
        ];
        return $this->success('get message success',$data);
    }


    /**
     * @description:房东欠款警告
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function landlordArrearsWarning(array $input)
    {
        $order_id = $input['order_id'];
        $landlord_user_id = LandlordOrder::where('id',$order_id)->pluck('user_id')->first();
        $landlord_name = Landlord::where('user_id',$landlord_user_id)->pluck('landlord_name')->first();
        $landlord_email = Landlord::where('user_id',$landlord_user_id)->pluck('email')->first();
        $landlord_address = Landlord::where('user_id',$landlord_user_id)->pluck('address')->first();
        $landlord_phone = Landlord::where('user_id',$landlord_user_id)->pluck('phone')->first();
        $arrears_fee = OrderArrears::where('order_id',$order_id)->sum('need_pay_fee');
        $providers_id = LandlordOrder::where('id',$order_id)->pluck('providers_id')->first();
        $providers_email = Providers::where('id',$providers_id)->pluck('email')->first();
        $providers_company_name = Providers::where('id',$providers_id)->pluck('service_name')->first();
        $providers_name = Providers::where('id',$providers_id)->pluck('first_name')->first();
        $providers_phone = Providers::where('id',$providers_id)->pluck('phone')->first();
        $date = date('Y-m-d');
        $content = "
                    $date
                    $landlord_name
                    $landlord_address
            Dear
           We have not received your payment of $ $arrears_fee. Please arrange this payment to be paid as soon as you receive this message. 
We might have no choice but to take further action if this matter can not be resloved soon. 
If you already paid this, please ignore this message.
We would be appreciated if you can make this payment as soon as possible.
Please contact with us if you have any questions, thank you.  
            Form:
                    $providers_name
                    $providers_phone
                    $providers_email 
            Date:  	$date  
        ";

        $data = [
            'landlord_user_id'  => $landlord_user_id,
            'content'           => $content,
            'send_email'        => $landlord_email,
            'send_phone'        => $landlord_phone,
        ];
        return $this->success('get message success',$data);
    }



    /**
     * @description:发送欠款提示通知
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendNote(array $input)
    {
        $data = [
            'user_id'           => $input['user_id'],
            'receive_user_id'   => $input['receive_user_id'],
            'contract_id'       => $input['contract_id'],
            'content'           => $input['content'],
            'msg_send'          => $input['msg_send'],
            'email_send'        => $input['email_send'],
            'paper_send'        => $input['paper_send'],
            'created_at'        => date('Y-m-d H:i:s',time()),
        ];
        $res = SendMessage::insert($data);
        // 发送email
        if($input['email_send'] == 1){
            $mail_to = $input['send_email'];
            $subject = 'note mail';
            Mail::send('email.note',['content' => $input['content']],function($code) use($mail_to,$subject){
                $code->to($mail_to)->subject($subject);
            });
        }
        // 发送短信
        if($input['msg_send'] == 1){
            $url = 'http://ngrok.zhan2345.com:8083/sms/'.$input['send_phone'].'/We have send you a email to'.$input['send_email'].', please check it. thank you ';
            curlGet($url);
        }
        return $this->success('get message success');
    }
}