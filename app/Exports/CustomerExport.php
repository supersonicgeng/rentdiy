<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;    // 导出 0 原样显示，不为 null
use Maatwebsite\Excel\Concerns\WithTitle;    // 设置工作䈬名称
use Maatwebsite\Excel\Concerns\WithHeadings;    //设置标题

class CustomerExport implements FromCollection, WithTitle, WithStrictNullComparison, WithHeadings
{

    public $data;
    public $payways;

    public function __construct($data, $payways)
    {
        $this->data = $data;
        $this->payways = $payways;

    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $customer = [];

        foreach ($this->data as $k => $v) {

            $customer[$k]['id'] = $v->id;
            $customer[$k]['pname'] = $v->pname;
            $customer[$k]['username'] = $v->username;
            $customer[$k]['wx_num'] = $v->wx_num;
            $customer[$k]['member_type'] = $this->member_level($v->member_type);
            $customer[$k]['time'] = $v->member_start ? date('Y-m-d', strtotime($v->member_start)) . '至' . date('Y-m-d', strtotime($v->member_validity)) : '/';
            $customer[$k]['invit_code'] = $v->invit_code;
            $customer[$k]['allowance'] = $v->allowance;
            $customer[$k]['income'] = $v->income + $v->forecast;
            $customer[$k]['balance'] = $v->balance;
            $customer[$k]['already_withdrawal'] = $v->already_withdrawal;

            $customer[$k]['confirmed_income'] = $v->confirmed_income;
            $customer[$k]['withdraw_ing'] = $v->withdraw_ing;
            $customer[$k]['withdraw_month'] = $v->withdraw_month;

            $customer[$k]['total_price'] = $v->total_price;
            $customer[$k]['order_nums'] = $v->order_nums;
            $customer[$k]['invitation_num'] = $v->invitation_num;

            $customer[$k]['parent_id'] = $v->parent_id;
            $customer[$k]['grandpa_id'] = $v->grandpa_id;
            $customer[$k]['last_super'] = $v->last_super;
            $customer[$k]['first_fans'] = $v->first_fans;
            $customer[$k]['second_fans'] = $v->second_fans;
            $customer[$k]['status'] = $v->status ? '正常' : '冻结';
            $customer[$k]['created_at'] = $v->created_at;
        }

        return collect($customer);
    }

    public function title(): string
    {
        // 设置工作䈬的名称
        return $this->payways;
    }

    //首行标题
    public function headings(): array
    {
        return [
            '用户ID',
            '用户昵称',
            '手机号',
            '微信号',
            '会员当前级别',
            '级别期限',
            '邀请码',
            '管理津贴',
            '累计预估收益',
            '可提现金额',
            '已提现金额',
            '累计可提现总额',
            '提现中金额',
            '本月新增可提现',
            '成交总额',
            '订单数',
            '邀请人数',
            '上级ID',
            '上上级ID',
            'SVPID',
            '第一市场人数',
            '第二市场人数',
            '账号状态',
            '注册时间',
        ];
    }

    /**
     * 会员等级
     */
    public function member_level($data)
    {
        if ($data == 1) {
            return '超级会员';
        } elseif ($data == 2) {
            return '合伙人';
        } elseif ($data == 3) {
            return '超级合伙人';
        }elseif ($data == 4){
            return '普通会员';
        }
    }

}
