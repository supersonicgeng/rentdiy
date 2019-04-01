<?php
/**
 * 表格处理层
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/13 0013
 * Time: 上午 9:07
 */

namespace App\Services;

use App\Model\Driver;
use App\Model\Img;
use App\Model\PinYin;
use App\Model\Plant;
use App\Model\Region;
use App\Model\RouteItems;
use App\Model\Routes;
use App\Model\Vehicle;
use App\User;
use Excel;
use DB;
use Illuminate\Support\Facades\Validator;

class ExcelService extends CommonService
{
    private $rootPath = 'storage/excel/';

    public function import($excel_name)
    {
        $filePath = $this->rootPath . $excel_name;
        Excel::load($filePath, function ($reader) {
            $data = $reader->all();
            dd($data);
        });
    }

    public function export()
    {

    }

    public function importRegion($excel_name)
    {
        $filePath = $this->rootPath . $excel_name;
        $region   = new Region();
        $pinyin   = new PinYin();
        Excel::load($filePath, function ($reader) use ($region, $pinyin) {
            $data       = $reader->all();
            $data_array = [];
            foreach ($data as $k => $item) {
                if ($k > 0 && $k < 3751) {
                    $data_array[] = [
                        'id'          => $item[0],
                        'name'        => $item[1],
                        'parent_id'   => $item[2],
                        'short_name'  => $item[3],
                        'level'       => $item[4],
                        'city_code'   => $item[5],
                        'zip_code'    => $item[6],
                        'merger_name' => $item[7],
                        'lng'         => $item[8],
                        'lat'         => $item[9],
                        'full_pinyin' => $item[10],
                        'pinyin'      => $pinyin->pinyin1($item[3]),
                    ];
                }
            }
            $region->insert($data_array);
        });
    }

    //excel文件上传
    public function excelUpload($file, $type)
    {
        $mimeType   = $file->getMimeType();
        $extend     = $file->getClientOriginalExtension();
        $allowArray = [
            'application/vnd.ms-office'
        ];
        if (!in_array($mimeType, $allowArray)) {
            return ['code' => 1, 'msg' => '文件类型错误!', 'data' => null];
        }
        if ($file->getClientSize() > (1024 * 1024 * 20)) {
            return ['code' => 1, 'msg' => '超出文件大小限制,文件大小请勿超过20M!', 'data' => null];
        }
        $path = storage_path(Img::$SAVE_PATH . Img::$EXCEL . DIRECTORY_SEPARATOR . date('Y-m-d') . DIRECTORY_SEPARATOR . $type);
        if (!file_exists(storage_path(Img::$SAVE_PATH . Img::$EXCEL))) {
            mkdir(storage_path(Img::$SAVE_PATH . Img::$EXCEL));
        }
        if (!file_exists(storage_path(Img::$SAVE_PATH . Img::$EXCEL . DIRECTORY_SEPARATOR . date('Y-m-d')))) {
            mkdir(storage_path(Img::$SAVE_PATH . Img::$EXCEL . DIRECTORY_SEPARATOR . date('Y-m-d')));
        }
        if (!file_exists($path)) {
            mkdir($path);
        }
        $excel = uniqid('', true) . '.' . $extend;
        $file->move($path, $excel);
        return ['code' => 0, 'data' => DIRECTORY_SEPARATOR . date('Y-m-d') . DIRECTORY_SEPARATOR . $type . DIRECTORY_SEPARATOR . $excel, 'msg' => ''];
    }


    //路线excel导入
    public function routeImportExcel($filePath)
    {
        $realFilePath = storage_path(Img::$SAVE_PATH . Img::$EXCEL . $filePath);
        $error        = [];
        Excel::load($realFilePath, function ($reader) use (&$error) {
            $data  = $reader->get()->toArray();
            $title = [
                '地点',
                '路线代码',
                '路线名称',
                '运输模式(FTL,MR)',
                '路线路径',
            ];
            $mode  = [
                'FTL' => 0,
                'MR'  => 1,
            ];
            if ($title !== array_filter(@$data[0][0])) {
                $error = ['code' => 1, 'msg' => '模板格式错误'];
                return false;
            }
            $allRouteCount = count($data[0]) - 1;
            $errorMsg      = '';
            for ($i = 1; $i <= $allRouteCount; $i++) {
                $insertData               = [];
                $insertData['area_name']  = $data[0][$i][0];
                $insertData['route_code'] = $data[0][$i][1];
                $insertData['route_name'] = $data[0][$i][2];
                $insertData['mode']       = @$mode[$data[0][$i][3]] ?: 0;
                $routeItemArr             = explode(',', $data[0][$i][4]);
                $insertData['route_path'] = '';
                foreach ($routeItemArr as $v) {
                    $insertData['route_path'] .= RouteItems::where('applier_code', $v)->value('id') . ',';
                }
                $insertData['route_path'] = substr($insertData['route_path'], 0, strlen($insertData['route_path']) - 1);
                $res                      = $this->routeImportExcelValidate($insertData);
                if ($res['code'] != 0) {
                    $errorMsg .= '路线导入 第' . ($i + 1) . '行' . $res['msg'] . ' 未导入成功!<br/>';
                    continue;
                } else {
                    Routes::insert($insertData);
                }
            }
            if ($errorMsg) {
                $error = ['code' => 2, 'msg' => $errorMsg];
            } else {
                $error = ['code' => 0, 'msg' => '导入成功'];
            }
        });
        return $error;
    }

    //路线导入验证
    public function routeImportExcelValidate($data)
    {
        $rule      = [
            'area_name'  => [
                'required',
                'max:50',
            ],
            'route_code' => [
                'required',
                'max:100',
                'unique:routes,route_code'
            ],
            'route_name' => [
                'required',
                'max:100',
                'unique:routes,route_name'
            ],
            'mode'       => [
                'required',
            ],
            'route_path' => [
                'required'
            ]
        ];
        $msg       = [
            'area_name.required'  => '请输入地区',
            'mode.required'       => '请选择运输模式',
            'route_code.required' => '请输入路线代码',
            'route_name.required' => '请输入路线名字',
            'route_path.required' => '请选择路线路径',
            'area_name.max'       => '供应商名字不得超过:max',
            'route_code.max'      => '路线代码不得超过:max',
            'route_name.max'      => '路线名字不得超过:max',
            'route_name.unique'   => '路线名称重复',
            'route_code.unique'   => '路线代码重复'
        ];
        $validator = Validator::make($data, $rule, $msg)->after(function ($validate) use ($data) {

        });
        if ($validator->fails()) {
            return ['code' => 2, 'msg' => $validator->errors()->first()];
        } else {
            return ['code' => 0];
        }
    }

    //车辆excel导入
    public function carImportExcel($filePath)
    {
        $realFilePath = storage_path(Img::$SAVE_PATH . Img::$EXCEL . $filePath);
        $error        = [];
        Excel::load($realFilePath, function ($reader) use (&$error) {
            $data    = $reader->get()->toArray();
            $title   = [
                '车牌号',
                '车辆类型(1重型半挂牵引车、2重型半挂车、3重型货车)',
                '所属公司（1公司车辆、2社会车辆）',
                '品牌型号',
                '行驶类型（1长途、2短途、3JIS）',
                '车队长手机号',
                '车辆季审日期',
                '车辆年审日期',
                '保险到期日',
            ];
            $carType = [
                '重型半挂牵引车' => 1,
                '重型半挂车'   => 2,
                '重型货车'    => 3
            ];
            $company = [
                '公司车辆' => 1,
                '社会车辆' => 2
            ];
            $dType   = [
                '长途'  => 1,
                '短途'  => 2,
                'IIS' => 3
            ];
            if ($title !== array_filter(@$data[0][0])) {
                $error = ['code' => 1, 'msg' => '模板格式错误!'];
                return false;
            }
            $errorMsg      = '';
            $allRouteCount = count($data[0]) - 1;
            for ($i = 1; $i <= $allRouteCount; $i++) {
                $insertData                  = [];
                $insertData['number']        = strtoupper($data[0][$i][0]);
                $insertData['vehicle_type']  = @$carType[$data[0][$i][1]];
                $insertData['type']          = @$company[$data[0][$i][2]] ?: 1;
                $insertData['brand']         = $data[0][$i][3];
                $insertData['driver_type']   = @$dType[$data[0][$i][4]] ?: 1;
                $insertData['main_driver']   = User::where('phone', $data[0][$i][5])->value('id');
                $insertData['quarter']       = date('Y-m-d', strtotime($data[0][$i][6]));
                $insertData['year']          = date('Y-m-d', strtotime($data[0][$i][7]));
                $insertData['insurance']     = date('Y-m-d', strtotime($data[0][$i][8]));
                $insertData['first_officer'] = 0;
                $insertData['created_at']    = date('Y-m-d H:i:s', time());
                $insertData['updated_at']    = date('Y-m-d H:i:s', time());
                $res                         = $this->carImportExcelValidate($insertData);
                if ($res['code'] != 0) {
                    $errorMsg .= '车辆导入 第' . ($i + 1) . '行' . $res['msg'] . ' 未导入成功!<br/>';
                    continue;
                } else {
                    Vehicle::insert($insertData);
                }
            }
            if ($errorMsg) {
                $error = ['code' => 2, 'msg' => $errorMsg];
            } else {
                $error = ['code' => 0, 'msg' => '导入成功'];
            }
        });

        return $error;
    }

    //车辆导入验证
    public function carImportExcelValidate($data)
    {
        $rule      = [
            'number'       => 'required|size:7|unique:vehicles,number',
            'vehicle_type' => 'required',
            'type'         => 'required',
            'driver_type'  => 'required',
            'brand'        => 'required|max:20',
            'main_driver'  => 'required',
            'quarter'      => 'required',
            'year'         => 'required',
            'insurance'    => 'required',
        ];
        $msg       = [
            'number.required'       => '车牌号必须',
            'number.unique'         => '车牌号已被使用',
            'number.size'           => '车牌号长度错误',
            'vehicle_type.required' => '车辆类型有误',
            'type.required'         => '车辆所属有误',
            'driver_type.required'  => '运营属性有误',
            'brand.required'        => '品牌型号必须',
            'main_driver.required'  => '请输入车队管理员',
            'quarter.required'      => '请输入季审日期',
            'year.required'         => '请输入年审日期',
            'insurance.required'    => '请输入保险日期',
        ];
        $validator = Validator::make($data, $rule, $msg);
        if ($validator->fails()) {
            return ['code' => 2, 'msg' => $validator->errors()->first()];
        } else {
            return ['code' => 0];
        }
    }


    //任务excel导入
    public function plantImportExcel($filePath)
    {
        $realFilePath = storage_path(Img::$SAVE_PATH . Img::$EXCEL . $filePath);
        $error        = [];
        Excel::load($realFilePath, function ($reader) use (&$error) {
            $data = $reader->get()->toArray();
            //主营任务数据
            $majorPlant = @$data[0];
            //倒短任务数据
            $ddPlant = @$data[1];
            //临时任务-抢单
            $catchPlant = @$data[2];
            //临时任务-报价
            $biddingPlant = @$data[3];
            $errorMsg     = '';
            if (count($this->multiArrayFilter($majorPlant)) > 1) {
                $error = $this->majorPlantImportExcel($majorPlant);
                if ($error['code'] !== 0) {
                    $errorMsg .= $error['msg'];
                }
            }
            if (count($this->multiArrayFilter($ddPlant)) > 1) {
                $error = $this->ddPlantImportExcel($ddPlant);
                if ($error['code'] !== 0) {
                    $errorMsg .= $error['msg'];
                }
            }
            if (count($this->multiArrayFilter($catchPlant)) > 1) {
                $error = $this->catchPlantImportExcel($catchPlant);
                if ($error['code'] !== 0) {
                    $errorMsg .= $error['msg'];
                }
            }
            if (count($this->multiArrayFilter($biddingPlant)) > 1) {
                $error = $this->biddingPlantImportExcel($biddingPlant);
                if ($error['code'] !== 0) {
                    $errorMsg .= $error['msg'];
                }
            }
            if ($errorMsg) {
                $error ['code'] = 1;
                $error['msg']   = $errorMsg;
            } else {
                $error['code'] = 0;
                $error['msg']  = '导入成功';
            }
        });
        return $error;
    }

    public function multiArrayFilter($data)
    {
        $ndata = [];
        foreach ($data as $k => $v) {
            if (empty(array_filter($v))) {
                continue;
            }
            $ndata[] = $v;
        }
        return $ndata;
    }

    //报价任务 excel导入
    public function biddingPlantImportExcel($biddingPlant)
    {
        $title = ['车辆车牌号', '路线代码', '始发地-省', '始发地-市', '始发地-区', '始发地详细地址', '目的地-省', '目的地-市', '目的地-区', '目的地详细地址', '订单类型(提货,返空)', '挂车牌号(非必填)'];
        if (array_filter($biddingPlant[0]) !== $title) {
            return ['code' => 1, 'msg' => '报价任务模板格式错误'];
        }
        $order_type = [
            '提货' => 0,
            '返空' => 1
        ];
        $errorMsg   = '';
        $allCount   = count($biddingPlant) - 1;
        for ($i = 1; $i <= $allCount; $i++) {
            $data                                = [];
            $data['type']                        = Plant::$TYPE_LSBJ;
            $data['type_show']                   = Plant::$TYPE_SHOW_ZYBJYW;
            $data['car_id']                      = $this->getVidByCarNo($biddingPlant[$i][0]);
            $data['route_id']                    = $this->getRidByRcode($biddingPlant[$i][1]);
            $data['origin_place_province_id']    = $this->getRegionsId($biddingPlant[$i][2], 1);
            $data['origin_place_city_id']        = $this->getRegionsId($biddingPlant[$i][3], 2);
            $data['origin_place_area_id']        = $this->getRegionsId($biddingPlant[$i][4], 3);
            $data['origin_place_detail_address'] = $biddingPlant[$i][5];
            $data['destination_province_id']     = $this->getRegionsId($biddingPlant[$i][6], 1);
            $data['destination_city_id']         = $this->getRegionsId($biddingPlant[$i][7], 2);
            $data['destination_area_id']         = $this->getRegionsId($biddingPlant[$i][8], 3);
            $data['destination_detail_address']  = $biddingPlant[$i][9];
            $data['is_open']                     = Plant::$MISSION_OPEN_STATUS_ON;
            $data['status']                      = Plant::$MISSION_TYPE_YCJ;
            $data['order_type']                  = @$order_type[$biddingPlant[$i][10]];
            $car_type                            = $this->getCarType($biddingPlant[$i][0]);
            $data['car_type']                    = $car_type;
            $data['g_number']                    = !$car_type || $car_type == 2 ? '' : $biddingPlant[$i][11];
            $res                                 = $this->plantImportExcelValidate($data);
            if ($res['code'] != 0) {
                $errorMsg .= '报价任务 第' . ($i + 1) . '行' . $res['msg'] . ' 未导入成功!<br/>';
                continue;
            } else {
                service('Plant')->plantAdd($data);
            }
        }
        if ($errorMsg) {
            return ['code' => 2, 'msg' => $errorMsg];
        }
        return ['code' => 0, '导入成功!'];
    }

    //临时任务-抢单 excel导入
    public function catchPlantImportExcel($catchPlant)
    {
        $title = ['车辆车牌号', '路线代码', '始发地-省', '始发地-市', '始发地-区', '始发地详细地址', '目的地-省', '目的地-市', '目的地-区', '目的地详细地址', '奖金', '订单类型(提货,返空)', '挂车牌号(非必填)'];
        if (array_filter($catchPlant[0]) !== $title) {
            return ['code' => 1, 'msg' => '抢单任务模板格式错误'];
        }
        $order_type = [
            '提货' => 0,
            '返空' => 1
        ];
        $errorMsg   = '';
        $allCount   = count($catchPlant) - 1;
        for ($i = 1; $i <= $allCount; $i++) {
            $data                                = [];
            $data['type']                        = Plant::$TYPE_LSQD;
            $data['type_show']                   = Plant::$TYPE_SHOW_LXQDYW;
            $data['car_id']                      = $this->getVidByCarNo($catchPlant[$i][0]);
            $data['route_id']                    = $this->getRidByRcode($catchPlant[$i][1]);
            $data['origin_place_province_id']    = $this->getRegionsId($catchPlant[$i][2], 1);
            $data['origin_place_city_id']        = $this->getRegionsId($catchPlant[$i][3], 2);
            $data['origin_place_area_id']        = $this->getRegionsId($catchPlant[$i][4], 3);
            $data['origin_place_detail_address'] = $catchPlant[$i][5];
            $data['destination_province_id']     = $this->getRegionsId($catchPlant[$i][6], 1);
            $data['destination_city_id']         = $this->getRegionsId($catchPlant[$i][7], 2);
            $data['destination_area_id']         = $this->getRegionsId($catchPlant[$i][8], 3);
            $data['destination_detail_address']  = $catchPlant[$i][9];
            $data['money']                       = $catchPlant[$i][10];
            $data['order_type']                  = @$order_type[$catchPlant[$i][11]];
            $car_type                            = $this->getCarType($catchPlant[$i][0]);
            $data['car_type']                    = $car_type;
            $data['g_number']                    = !$car_type || $car_type == 2 ? '' : $catchPlant[$i][12];
            $data['is_open']                     = Plant::$MISSION_OPEN_STATUS_ON;
            $data['status']                      = Plant::$MISSION_TYPE_YCJ;
            $res                                 = $this->plantImportExcelValidate($data);
            if ($res['code'] != 0) {
                $errorMsg .= '抢单任务 第' . ($i + 1) . '行' . $res['msg'] . ' 未导入成功!<br/>';
                continue;
            } else {
                service('Plant')->plantAdd($data);
            }
        }
        if ($errorMsg) {
            return ['code' => 2, 'msg' => $errorMsg];
        }
        return ['code' => 0, '导入成功!'];
    }

    //倒短业务excel导入
    public function ddPlantImportExcel($ddPlant)
    {
        $title = ['车辆车牌号', '司机手机号', '始发地-省', '始发地-市', '始发地-区', '始发地详细地址', '目的地-省', '目的地-市', '目的地-区', '目的地详细地址', '订单类型(提货,返空)', '挂车牌号(非必填)'];
        if (array_filter($ddPlant[0]) !== $title) {
            return ['code' => 1, 'msg' => '倒短任务模板格式错误'];
        }
        $order_type = [
            '提货' => 0,
            '返空' => 1
        ];
        $errorMsg   = '';
        $allCount   = count($ddPlant) - 1;
        for ($i = 1; $i <= $allCount; $i++) {
            $data                                = [];
            $data['type']                        = Plant::$TYPE_DD;
            $data['type_show']                   = Plant::$TYPE_SHOW_ZYJISYW;
            $data['car_id']                      = $this->getVidByCarNo($ddPlant[$i][0]);
            $data['main_driver_id']              = $this->getDidByPhone($ddPlant[$i][1]);
            $data['origin_place_province_id']    = $this->getRegionsId($ddPlant[$i][2], 1);
            $data['origin_place_city_id']        = $this->getRegionsId($ddPlant[$i][3], 2);
            $data['origin_place_area_id']        = $this->getRegionsId($ddPlant[$i][4], 3);
            $data['origin_place_detail_address'] = $ddPlant[$i][5];
            $data['destination_province_id']     = $this->getRegionsId($ddPlant[$i][6], 1);
            $data['destination_city_id']         = $this->getRegionsId($ddPlant[$i][7], 2);
            $data['destination_area_id']         = $this->getRegionsId($ddPlant[$i][8], 3);
            $data['destination_detail_address']  = $ddPlant[$i][9];
            $data['order_type']                  = @$order_type[$ddPlant[$i][10]];
            $data['car_type']                    = $this->getCarType($ddPlant[$i][0]);
            $data['g_number']                    = !$this->getCarType($ddPlant[$i][0]) || $this->getCarType($ddPlant[$i][0]) == 2 ? '' : $ddPlant[$i][11];
            $data['is_open']                     = Plant::$MISSION_OPEN_STATUS_ON;
            $res                                 = $this->plantImportExcelValidate($data);
            if ($res['code'] != 0) {
                $errorMsg .= '倒短业务 第' . ($i + 1) . '行' . $res['msg'] . ' 未导入成功!<br/>';
                continue;
            } else {
                service('Plant')->plantAdd($data);
            }
        }
        if ($errorMsg) {
            return ['code' => 2, 'msg' => $errorMsg];
        }
        return ['code' => 0, '导入成功!'];
    }

    //主营业务excel导入
    public function majorPlantImportExcel($majorPlant)
    {
        $title = ['车辆车牌号', '司机手机号', '路线代码', '订单号', '始发地-省', '始发地-市', '始发地-区', '始发地详细地址', '目的地-省', '目的地-市', '目的地-区', '目的地详细地址', '任务类型(主营长途业务,主营短途业务,零星业务)', '订单类型(提货,返空)', '挂车牌号(非必填)'];
        if (array_filter($majorPlant[0]) !== $title) {
            return ['code' => 1, 'msg' => '主营任务模板格式错误'];
        }
        $errorMsg        = '';
        $allCount        = count($majorPlant) - 1;
        $plant_type_show = [
            '主营长途业务' => 0,
            '主营短途业务' => 1,
            '零星业务'   => 2,
        ];
        $order_type      = [
            '提货' => 0,
            '返空' => 1
        ];
        for ($i = 1; $i <= $allCount; $i++) {
            $data                                = [];
            $data['type']                        = Plant::$TYPE_ZY;
            $data['car_id']                      = $this->getVidByCarNo($majorPlant[$i][0]);
            $data['main_driver_id']              = $this->getDidByPhone($majorPlant[$i][1]);
            $data['route_id']                    = $this->getRidByRcode($majorPlant[$i][2]);
            $data['order_num']                   = $majorPlant[$i][3];
            $data['origin_place_province_id']    = $this->getRegionsId($majorPlant[$i][4], 1);
            $data['origin_place_city_id']        = $this->getRegionsId($majorPlant[$i][5], 2);
            $data['origin_place_area_id']        = $this->getRegionsId($majorPlant[$i][6], 3);
            $data['origin_place_detail_address'] = $majorPlant[$i][7];
            $data['destination_province_id']     = $this->getRegionsId($majorPlant[$i][8], 1);
            $data['destination_city_id']         = $this->getRegionsId($majorPlant[$i][9], 2);
            $data['destination_area_id']         = $this->getRegionsId($majorPlant[$i][10], 3);
            $data['destination_detail_address']  = $majorPlant[$i][11];
            $data['type_show']                   = @$plant_type_show[$majorPlant[$i][12]];
            $data['order_type']                  = @$order_type[$majorPlant[$i][13]];
            $data['is_open']                     = Plant::$MISSION_OPEN_STATUS_ON;
            $data['car_type']                    = $this->getCarType($majorPlant[$i][0]);
            $data['g_number']                    = !$this->getCarType($majorPlant[$i][0]) || $this->getCarType($majorPlant[$i][0]) == 2 ? '' : $majorPlant[$i][14];
            $res                                 = $this->plantImportExcelValidate($data);
            if ($res['code'] != 0) {
                $errorMsg .= '主营业务 第' . ($i + 1) . '行' . $res['msg'] . ' 未导入成功!<br/>';
                continue;
            } else {
                service('Plant')->plantAdd($data);
            }
        }
        if ($errorMsg) {
            return ['code' => 2, 'msg' => $errorMsg];
        }
        return ['code' => 0, '导入成功!'];
    }

    public function plantImportExcelValidate(Array $data)
    {
        $rule      = [
            'type_show'                   => 'required',
            'car_type'                    => 'required',
            'car_id'                      => 'required',
            'origin_place_province_id'    => 'required',
            'origin_place_city_id'        => 'required',
            'origin_place_area_id'        => 'required',
            'origin_place_detail_address' => 'required',
            'destination_province_id'     => 'required',
            'destination_city_id'         => 'required',
            'destination_area_id'         => 'required',
            'destination_detail_address'  => 'required',
            'route_id'                    => 'required_if:type_show,' . Plant::$TYPE_SHOW_ZYCTYW . ',' . Plant::$TYPE_SHOW_ZYDTYW . ',' . Plant::$TYPE_SHOW_LXYW . ',' . Plant::$TYPE_SHOW_LXQDYW . ',' . Plant::$TYPE_SHOW_ZYBJYW,
            'main_driver_id'              => 'required_if:type_show,' . Plant::$TYPE_SHOW_ZYCTYW . ',' . Plant::$TYPE_SHOW_ZYDTYW . ',' . Plant::$TYPE_SHOW_LXYW . ',' . Plant::$TYPE_SHOW_ZYJISYW,
            'money'                       => 'required_if:type_show,' . Plant::$TYPE_SHOW_LXQDYW,
            'g_number'                    => 'required_if:car_type,1,2',
            'order_type'                  => 'required'
        ];
        $msg       = [
            'route_id.required_if'                 => '路线输入有误',
            'main_driver_id.required_if'           => '主驾驶输入有误',
            'money.required_if'                    => '请输入金额',
            'car_id.required'                      => '车牌号输入有误',
            'origin_place_province_id.required'    => '始发地-省有误',
            'origin_place_city_id.required'        => '始发地-市有误',
            'origin_place_area_id.required'        => '始发地-区有误',
            'origin_place_detail_address.required' => '始发地详细地址不得为空',
            'destination_province_id.required'     => '目的地-省有误',
            'destination_city_id.required'         => '目的地-市有误',
            'destination_area_id.required'         => '目的地-区有误',
            'destination_detail_address.required'  => '目的地详细地址不得为空',
            'g_number.required_if'                 => '挂车牌号必须',
            'order_type.required'                  => '订单类型有误',
            'car_type.required'                    => '车牌号输入有误'
        ];
        $validator = Validator::make($data, $rule, $msg);
        if ($validator->fails()) {
            return ['code' => 2, 'msg' => $validator->errors()->first()];
        } else {
            return ['code' => 0];
        }
    }

    //根据车牌号获取车辆主键
    public function getVidByCarNo($number)
    {
        return Vehicle::where('number', $number)->value('id');
    }

    //根据路线代码获取路线主键
    public function getRidByRcode($rCode)
    {
        return Routes::where('route_code', $rCode)->value('id');
    }

    //根据司机手机号获取司机主键
    public function getDidByPhone($phone)
    {
        return Driver::where('phone', $phone)->value('id');
    }

    //根据省市区名字查询主键
    public function getRegionsId($name, $level)
    {
        return Region::where('level', $level)->where('name', 'like', '%' . $name . '%')->value('id');
    }

    //根据车牌号获取车辆类型
    public function getCarType($number)
    {
        return Vehicle::where('number', $number)->value('vehicle_type');
    }
}