<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;    // 导出 0 原样显示，不为 null
use Maatwebsite\Excel\Concerns\WithTitle;    // 设置工作䈬名称
use Maatwebsite\Excel\Concerns\WithHeadings;    //设置标题

class WithdrawExport implements FromCollection, WithTitle, WithStrictNullComparison, WithHeadings
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
        foreach ($this->data as $k => $v) {
            $v->order_sn = "\t" . $v->order_sn;
            $v->pass_flag = $this->check_status($v->pass_flag);
        }

        return $this->data;
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
            '用户ID',
            '提现订单号',
            '提现金额',
            '提现账号',
            '申请时间',
            '到账时间',
            '审核状态',
            '审核人',
            '审核时间',
            '原因',
        ];
    }

    /***
     * 审核状态
     */
    public function check_status($status)
    {
        switch ($status) {
            case 0:
                return '待审核';
                break;
            case 1:
                return '通过';
                break;
            case -1:
                return '不通过';
                break;
        }
    }

    /***
     * 会员等级
     */
    public function member_type($member_type)
    {
        switch ($member_type) {
            case 1:
                return '会员';
                break;
            case 2:
                return '合伙人';
                break;
            case 3:
                return '超级合伙人';
                break;
            case 4:
                return '高级会员';
                break;
        }
    }
}
