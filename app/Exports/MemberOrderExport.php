<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;    // 导出 0 原样显示，不为 null
use Maatwebsite\Excel\Concerns\WithTitle;    // 设置工作䈬名称
use Maatwebsite\Excel\Concerns\WithHeadings;    //设置标题

class MemberOrderExport implements FromCollection, WithStrictNullComparison, WithTitle, WithHeadings
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
            $v->order_status = '已返佣';
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
            '订单号',
            '用户名',
            '用户ID',
            '升级前等级',
            '充值等级',
            '付款(元)',
            '上级ID',
            '上级津贴',
            '上上级ID',
            '上上级津贴',
            'SVIP一ID',
            'SVIP一津贴',
            'SVIP二ID',
            'SVIP二津贴',
            '平台津贴',
            '津贴状态',
            '下单时间'
        ];
    }

}
