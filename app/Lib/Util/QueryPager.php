<?php

namespace App\Lib\Util;

use Illuminate\Http\Request;
use Carbon\Carbon;

class QueryPager
{
    private $query = null;
    private $mappedFields = [];
    private $methodFields = [];

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function mapField($field, $mapper)
    {
        $this->mappedFields[$field] = $mapper;
    }

    public function setRefectionMethodField($field)
    {
        array_push($this->methodFields, $field);
    }

    public function queryWithoutPaginate(Array $input, $sort = null)
    {
        if (isset($sort)) {
            $query = $this->query->orderBy($sort);
        }

        $data = $this->invokeReflectionDatas($query->get());

        $result = [];

        foreach ($data as $item) {
            array_push($result, $this->getMappedDataItem($item));
        }

        return $result;
    }

    public function doPaginate(Array $input, $sort = null)
    {
        $pageNumber    = isset($input['pageNumber']) ? $input['pageNumber'] : 1;
        $pageSize      = $input['pageSize'];
        $getTotalCount = isset($input['getCount']) ? true : false;
        return  $this->paginate($pageNumber, $pageSize, $sort, $getTotalCount) ;
    }

    public function paginate($pageIndex, $pageSize, $sort, $getTotalCount = false)
    {
        //计算总记录数
        $queryCount = clone $this->query;
        $count      = $queryCount->count();
        if ($getTotalCount) {
            echo $count;
            exit;
        }
        if (isset($sort)) {
            $query = $this->query->orderBy($sort);
        }

        $offset = ($pageIndex - 1) * $pageSize;

        //记录数越界，清零
        if ($offset >= $count) {
            $offset = 0;
        }

        $data = $this->invokeReflectionDatas($query->skip($offset)->take($pageSize)->get());

        $result = [];

        foreach ($data as $item) {
            array_push($result, $this->getMappedDataItem($item));
        }

        return [
            'totalPage'  => ceil($count / $pageSize),
            'pageNumber' => $pageIndex,
            'hasMore'    => ceil($count / $pageSize) > $pageIndex,
            'list'       => $result
        ];
    }
    public function doPaginateSelect2(Array $input, $sort = null)
    {
        $pageNumber    = isset($input['pageNumber']) ? $input['pageNumber'] : 1;
        $pageSize      = $input['pageSize'];
        $getTotalCount = isset($input['getCount']) ? true : false;
        return  $this->paginateSelect2($pageNumber, $pageSize, $sort, $getTotalCount) ;
    }

    public function paginateSelect2($pageIndex, $pageSize, $sort, $getTotalCount = false){
        //计算总记录数
        $queryCount = clone $this->query;
        $count      = $queryCount->count();
        if ($getTotalCount) {
            echo $count;
            exit;
        }
        if (isset($sort)) {
            $query = $this->query->orderBy($sort);
        }

        $offset = ($pageIndex - 1) * $pageSize;

        //记录数越界，清零
        if ($offset >= $count) {
            $offset = 0;
        }

        $data = $this->invokeReflectionDatas($query->skip($offset)->take($pageSize)->get());

        $result = [];

        foreach ($data as $item) {
            array_push($result, $this->getMappedDataItem($item));
        }

        return [
            'total_count'  =>$count,
            'incomplete_results'    => ceil($count / $pageSize) > $pageIndex,
            'items'       => $result
        ];
    }

    private function invokeReflectionDatas($datas)
    {
        if (count($this->methodFields) > 0) {
            $result = [];

            $dataCount = count($datas);
            for ($i = 0; $i < $dataCount; $i++) {
                $dataItem    = $datas[$i];
                $dataInvoked = $dataItem->toArray();
                if ($i == 0) {
                    $itemClass = new \ReflectionClass(get_class($dataItem));
                }
                foreach ($this->methodFields as $methodName) {
                    $method      = $itemClass->getmethod($methodName);
                    $dataInvoked = array_merge($dataInvoked, [
                        $methodName => $method->invoke($dataItem)
                    ]);
                }

                array_push($result, $dataInvoked);
            }

            return $result;
        }

        return $datas->toArray();
    }

    private function getMappedDataItem($dataItem)
    {
        $mappedFields = $this->mappedFields;

        foreach ($mappedFields as $key => $map) {
            foreach ($map as $kvPair) {
                if ($dataItem[$key] == $kvPair['key']) {
                    $dataItem[$key . '_text'] = $kvPair['text'];
                    break;
                }
            }
        }

        return $dataItem;
    }

    /**
     * @description:分页版本2
     * @author: hkw <hkw925@qq.com>
     * @param array $input
     * @param null $sort
     * @return mixed
     */
    public function getPage(Array $input, $sort=null){
        $pageNumber = isset($input['pageNumber'])?$input['pageNumber']:1;
        $pageSize = $input['pageSize'];
        $getTotalCount = isset($input['getCount'])?true:false;
        //return $this->paginate($pageNumber, $pageSize, $sort,$getTotalCount);
        $queryCount = clone $this->query;
        $count = $queryCount->count();
        if($getTotalCount){
            echo $count;exit;
        }
        if (isset($sort)) {
            $query = $this->query->orderBy($sort);
        }

        $offset = ($pageNumber - 1) * $pageSize;

        //记录数越界，清零
        /*if ($offset >= $count) {
            $offset = 0;
        }*/
        $data = $query->skip($offset)->take($pageSize)->get();
        return $data;
    }
}
