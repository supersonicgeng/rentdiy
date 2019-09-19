<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;    // 导出 0 原样显示，不为 null
use Maatwebsite\Excel\Concerns\WithTitle;    // 设置工作䈬名称
use Maatwebsite\Excel\Concerns\WithHeadings;    //设置标题


class UsersExport implements FromCollection, WithTitle, WithStrictNullComparison, WithHeadings
{

    public $data;
    public $payways;

    public function __construct($data, $payways)
    {
        $this->data = $data;

    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        foreach ($this->data as $k => $v) {
            $v->tk_status = tb_order_status($v->tk_status);
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
            '商品平台',
            '订单号',
            '商品ID',
            '平台商品ID',
            '商品标题',
            '所属分类',
            '商品价格',
            '成交额',
            '成交量',
            '佣金比例',
            '佣金金额',
            '用户ID',
            '用户佣金',
            '上一级ID',
            '上级佣金',
            '上二级ID',
            '上上级佣金',
            'SVIPID',
            'SVIP佣金',
            '平台佣金',
            '订单状态',
            '下单时间'
        ];
    }


}
