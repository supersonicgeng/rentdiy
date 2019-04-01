<?php
/**
 * Created by PhpStorm.
 * User: huangkaiwang
 * Date: 2018/3/28
 * Time: 15:04
 */

namespace App\Services;


use App\Lib\Util\QueryPager;
use App\Model\Region;

class AddressService extends CommonService
{
//省市区三级联动获取数据
    public function getDataOfAddress(Array $input)
    {

        switch ($input['type']) {
            case 1:
                return $this->getProvince($input);
                break;
            case 2:
                return $this->getCity($input);
                break;
            case 3:
                return $this->getDistrict($input);
                break;
            default:
                return null;
        }
    }

    public function getProvince($input)
    {
        $query = Region::where('level', '=', 1);


        if (isset($input['search']) && !empty($input['search'])) {
            $query = $query->where('name', 'like', '%' . $input['search'] . '%');
        }

        $pager = new QueryPager($query);

        return $pager->doPaginateSelect2($input, 'id');
    }

    public function getCity($input)
    {
        $query = Region::where('level', '=', 2);

        if (isset($input['search']) && !empty($input['search'])) {
            $query = $query->where('name', 'like', '%' . $input['search'] . '%');
        }

        $query = $query->where('parent_id', '=', $input['province']);

        $pager = new QueryPager($query);

        return $pager->doPaginateSelect2($input, 'id');
    }

    public function getDistrict($input)
    {
        $query = Region::where('level', '=', 3);

        if (isset($input['search']) && !empty($input['search'])) {
            $query = $query->where('name', 'like', '%' . $input['search'] . '%');
        }

        $query = $query->where('parent_id', '=', $input['city']);

        $pager = new QueryPager($query);

        return $pager->doPaginateSelect2($input, 'id');
    }
}