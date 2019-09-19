<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;    // 导出 0 原样显示，不为 null
use Maatwebsite\Excel\Concerns\WithTitle;    // 设置工作䈬名称
use Maatwebsite\Excel\Concerns\WithHeadings;    //设置标题

class SingleWithdrawExport implements FromCollection, WithTitle, WithStrictNullComparison, WithHeadings
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

            $customer[$k]['month'] = $v->month;
            $customer[$k]['id'] = $v->id;
            $customer[$k]['username'] = $v->username;
            $customer[$k]['confirm_total'] = $v->confirm_total;
            $customer[$k]['withdraw_allow'] = $v->withdraw_allow;
            $customer[$k]['already_total'] = $v->already_total;
            $customer[$k]['withdraw_ing'] = $v->withdraw_img;
            $customer[$k]['withdraw_num'] = $v->withdraw_num;

        }

        return collect($customer);
    }

    public function title(): string
    {
        // 设置工作䈬的名称
        return $this->payways . '订单';
    }

    //首行标题
    public function headings(): array
    {
        return [
            '时间',
            '用户ID',
            '手机号',
            '累计收益',
            '可提现金额',
            '已提现金额',
            '申请提现金额',
            '提现单数',
        ];
    }


}
