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
        $content = 'Dear
            We have not received your payment of $'.$arrears_fee.' Please arrange this payment to be paid as soon as you receive this message. 
            We might have no choice but to take further action if this matter can not be resloved soon. 
            If you already paid this, please ignore this message.
            We would be appreciated if you can make this payment as soon as possible.
            Please contact with us if you have any questions, thank you.  ';
        $tenement_id = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_id')->first();
        $tenement_name = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_full_name')->first();
        $tenement_address = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_post_address')->first();
        $tenement_email = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_e_mail')->first();
        $tenement_phone = ContractTenement::where('contract_id',$contract_id)->pluck('tenement_phone')->first();
        $landlord_name = RentContract::where('id',$contract_id)->pluck('landlord_full_name')->first();
        $landlord_mobile = RentContract::where('id',$contract_id)->pluck('landlord_mobile_phone')->first();
        $landlord_email = RentContract::where('id',$contract_id)->pluck('landlord_e_mail')->first();
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
            'receive_user_id'   => $input['tenement_id'],
            'contract_id'       => $input['contract_id'],
            'content'           => $input['content'],
            'msg_send'          => $input['msg_send'],
            'email_send'        => $input['email_send'],
            'paper_send'        => $input['paper_send'],
            'msg_type'          => $input['msg_type'],
            'send_status'       => $input['send_status'],
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