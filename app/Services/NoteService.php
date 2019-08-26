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
            'msg_type'          => $input['msg_type'],
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