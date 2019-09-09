<?php
/**
 * 充值服务层
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/1 0001
 * Time: 下午 4:25
 */

namespace App\Services;


use App\Lib\Util\QueryPager;
use App\Model\Bond;
use App\Model\BondRefund;
use App\Model\BondTransfer;
use App\Model\ContractTenement;
use App\Model\OperatorRoom;
use App\Model\Region;
use App\Model\RentArrears;
use App\Model\RentContact;
use App\Model\RentContract;
use App\Model\RentHouse;
use App\Model\RentPic;
use App\Model\Verify;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ChargeService extends CommonService
{
    /**
     * @description:充值列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function chargeList(array $input)
    {
        $res = DB::table('charge')->get();
        return $this->success('get bond list success',$res);
    }



}