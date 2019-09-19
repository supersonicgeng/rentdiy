<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;    // 导出 0 原样显示，不为 null
use Maatwebsite\Excel\Concerns\WithTitle;    // 设置工作䈬名称
use Maatwebsite\Excel\Concerns\WithHeadings;    //设置标题

class PhoneOrderExport implements FromCollection, WithTitle, WithStrictNullComparison, WithHeadings
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
            $v->ostat = phoneStatus($v->ostat);
            $v->atime = Timeformat($v->atime);
            $v->dtime = $v->dtime==0 ? '':Timeformat($v->dtime);
            $v->mtime = Timeformat($v->mtime);
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
            '回收单号',
            '用户评估品牌',
            '用户评估机型',
            '用户评估价格',
            '实际评估价格',
            '佣金金额',
            '订单状态',
            '用户ID',
            '用户佣金',
            '上一级ID',
            '上一级佣金',
            '上二级ID',
            '上二级佣金',
            'SVIPID',
            'SVIP佣金',
            '平台佣金',
            '申请时间',
            '创建时间',
            '更新时间'
        ];
    }


}
