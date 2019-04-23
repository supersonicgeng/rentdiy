<?php
/**
 * 房屋主档服务层
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/1 0001
 * Time: 下午 4:25
 */

namespace App\Services;


use App\Lib\Util\QueryPager;
use App\Model\Region;
use App\Model\RentContact;
use App\Model\RentHouse;
use App\Model\RentPic;
use App\Model\Verify;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class HouseService extends CommonService
{
    /**
     * @description:建立房屋主档
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function addHouseList(array $input)
    {
        $user_id = $input['user_id'];
        $rent_category = $input['rent_category'];
        $model = new RentHouse();
        $user_role = \App\Model\User::where('id',$user_id)->first()['user_role'];
        $group_id = $model->max('group_id'); // 获得目前存入的最大group_id
        if($user_role == 1 || $user_role ==3 || $user_role == 5 || $user_role == 7){
            if($rent_category == 1){ // 新建整租房屋主档
                if($input['rent_period'] == 1){
                    $rent_fee_pre_week = $input['rent_fee']*7;
                }elseif ($input['rent_period'] == 2){
                    $rent_fee_pre_week = $input['rent_fee'];
                }elseif ($input['rent_period'] == 3){
                    $rent_fee_pre_week = $input['rent_fee']/4;
                }elseif ($input['rent_period'] == 4){
                    $rent_fee_pre_week = $input['rent_fee']/13;
                }elseif ($input['rent_period'] == 5){
                    $rent_fee_pre_week = $input['rent_fee']/26;
                }elseif ($input['rent_period'] == 6){
                    $rent_fee_pre_week = $input['rent_fee']/52;
                }
                $data = [
                    'user_id'               => $user_id,
                    'group_id'              => $group_id+1,
                    'rent_category'         => $rent_category,
                    'property_name'         => $input['property_name'],
                    'details'               => $input['details'],
                    'property_type'         => $input['property_type'],
                    'bathroom_no'           => $input['bathroom_no'],
                    'bedroom_no'            => $input['bedroom_no'],
                    'building_area'         => $input['building_area'],
                    'actual_area'           => $input['actual_area'],
                    'parking_no'            => $input['parking_no'],
                    'garage_no'             => $input['garage_no'],
                    'insurance_company'     => $input['insurance_company'],
                    'insurance_start_time'  => $input['insurance_start_time'],
                    'insurance_end_time'    => $input['insurance_end_time'],
                    'Region'                => $input['Region'],
                    'TA'                    => $input['TA'],
                    'District'              => $input['District'],
                    'address'               => $input['address'],
                    'lat'                   => $input['lat'],
                    'lon'                   => $input['lon'],
                    'short_words'           => implode(',',$input['short_words']),
                    'bus_station'           => $input['bus_station'],
                    'school'                => $input['school'],
                    'supermarket'           => $input['supermarket'],
                    'hospital'              => $input['hospital'],
                    /*'available_time'        => $input['available_time'],*/
                    'rent_period'           => $input['rent_period'],
                    'rent_fee'              => $input['rent_fee'],
                    'rent_fee_pre_week'     => @$rent_fee_pre_week,
                    'least_rent_time'       => $input['least_rent_time'],
                    'least_rent_method'     => $input['least_rent_method'],
                    'pre_rent'              => $input['pre_rent'],
                    'pre_rent_fee'          => $input['pre_rent_fee'],
                    'margin_rent'           => $input['margin_rent'],
                    'margin_rent_fee'       => $input['margin_rent_fee'],
                    'total_need_fee'        => $input['total_need_fee'],
                    'require_renter'        => $input['require_renter'],
                    'can_party'             => $input['can_party'],
                    'can_pet'               => $input['can_pet'],
                    'can_smoke'             => $input['can_smoke'],
                    'other_rule'            => $input['other_rule'],
                    'created_at'            => date('Y-m-d H:i:s',time()),
                ]; // 房屋主档数据
                $rent_house_id = $model->insertGetId($data); //获取房屋主档id
                // 添加图片
                $rent_pic = $input['house_pic'];
                static $error = 0;
                foreach ($rent_pic as $k => $v){
                    $pic_data = [
                        'rent_house_id' => $rent_house_id,
                        'house_pic'     => $v,
                        'created_at'    => date('Y-m-d H:i:s',time()),
                    ];
                    $res = RentPic::insert($pic_data);
                    if(!$res){
                        $error +=1;
                    }
                }
                // 添加联系人
                $contact_info = $input['contact_info'];
                foreach ($contact_info as $key => $value){
                    $contact_data = [
                        'rent_house_id' => $rent_house_id,
                        'contact_name'  => $value['contact_name'],
                        'contact_role'  => $value['contact_role'],
                        'e_mail'        => $value['e_mail'],
                        'phone'         => $value['phone'],
                        'created_at'    => date('Y-m-d H:i:s',time()),
                    ];
                    $res = RentContact::insert($contact_data);
                    if(!$res){
                        $error +=1;
                    }
                }
                if($rent_house_id && !$error){
                    return $this->success('rent_house_list add succcess');
                }else{
                    return $this->error('2','rent_house_list add failed, Pls try again');
                }
            }elseif ($rent_category == 2 || $rent_category == 3){ // 新建分租/室友房屋主档
                $room_info = $input['room_info'];
                static $error = 0;
                foreach ($room_info as $k => $v){
                    if($input['rent_period'] == 1){
                        $rent_fee_pre_week = $v['rent_fee']*7;
                    }elseif ($input['rent_period'] == 2){
                        $rent_fee_pre_week = $v['rent_fee'];
                    }elseif ($input['rent_period'] == 3){
                        $rent_fee_pre_week = $v['rent_fee']/4;
                    }elseif ($input['rent_period'] == 4){
                        $rent_fee_pre_week = $v['rent_fee']/13;
                    }elseif ($input['rent_period'] == 5){
                        $rent_fee_pre_week = $v['rent_fee']/26;
                    }elseif ($input['rent_period'] == 6){
                        $rent_fee_pre_week = $v['rent_fee']/52;
                    }
                    $data = [
                        'user_id'               => $user_id,
                        'group_id'              => $group_id+1,
                        'rent_category'         => $rent_category,
                        'property_name'         => $input['property_name'],
                        'details'               => $input['details'],
                        'property_type'         => $input['property_type'],
                        'bathroom_no'           => $input['bathroom_no'],
                        'bedroom_no'            => $input['bedroom_no'],
                        'building_area'         => $input['building_area'],
                        'actual_area'           => $input['actual_area'],
                        'parking_no'            => $input['parking_no'],
                        'garage_no'             => $input['garage_no'],
                        'insurance_company'     => $input['insurance_company'],
                        'insurance_start_time'  => $input['insurance_start_time'],
                        'insurance_end_time'    => $input['insurance_end_time'],
                        'Region'                => $input['Region'],
                        'TA'                    => $input['TA'],
                        'District'              => $input['District'],
                        'address'               => $input['address'],
                        'lat'                   => $input['lat'],
                        'lon'                   => $input['lon'],
                        'short_words'           => implode(',',$input['short_words']),
                        'bus_station'           => $input['bus_station'],
                        'school'                => $input['school'],
                        'supermarket'           => $input['supermarket'],
                        'hospital'              => $input['hospital'],
                        /*'available_time'        => $input['available_time'][$k],*/
                        'room_name'             => $v['room_name'],
                        'room_description'      => $v['room_description'],
                        'bed_no'                => $v['bed_no'],
                        'shower_room'           => $v['shower_room'],
                        'require_renter'        => $v['require_renter'],
                        'room_short_words'      => implode(',',$v['room_short_words']),
                        'rent_period'           => $v['rent_period'],
                        'rent_fee'              => $v['rent_fee'],
                        'rent_fee_pre_week'     => @$rent_fee_pre_week,
                        'least_rent_time'       => $v['least_rent_time'],
                        'least_rent_method'     => $v['least_rent_method'],
                        'pre_rent'              => $v['pre_rent'],
                        'pre_rent_fee'          => $v['pre_rent_fee'],
                        'margin_rent'           => $v['margin_rent'],
                        'margin_rent_fee'       => $v['margin_rent_fee'],
                        'total_need_fee'        => $v['total_need_fee'],
                        'can_party'             => $input['can_party'],
                        'can_pet'               => $input['can_pet'],
                        'can_smoke'             => $input['can_smoke'],
                        'other_rule'            => $input['other_rule'],
                        'created_at'            => date('Y-m-d H:i:s',time()),
                    ]; // 房屋主档数据
                    $rent_house_id = $model->insertGetId($data); //获取房屋主档id
                    if(!$rent_house_id){ // 没有添加主档成功
                        $error += 1;
                    }else{
                        // 添加图片
                        $rent_pic = $input['house_pic'][$k];
                        foreach ($rent_pic as $key=> $value){
                            $pic_data = [
                                'rent_house_id' => $rent_house_id,
                                'house_pic'     => $value,
                                'created_at'    => date('Y-m-d H:i:s',time()),
                            ];
                            $res = RentPic::insert($pic_data);
                            if(!$res){ // 没有添加图片成功
                                $error +=1;
                            }
                        }

                        // 添加联系人
                        $contact_info = $input['contact_info'];
                        foreach ($contact_info as $key => $value){
                            $contact_data = [
                                'rent_house_id' => $rent_house_id,
                                'contact_name'  => $value['contact_name'],
                                'contact_role'  => $value['contact_role'],
                                'e_mail'        => $value['e_mail'],
                                'phone'         => $value['phone'],
                                'created_at'    => date('Y-m-d H:i:s',time()),
                            ];
                            $res = RentContact::insert($contact_data);
                            if(!$res){
                                $error +=1;
                            }
                        }
                    }

                }
                if(!$error){
                    return $this->success('rent_house_list add succcess');
                }else{
                    return $this->error('2','rent_house_list add failed, Pls try again');
                }

            }elseif ($rent_category == 4){ //新建商业房屋主档
                $data = [
                    'user_id'               => $user_id,
                    'group_id'              => $group_id+1,
                    'rent_category'         => $rent_category,
                    'property_name'         => $input['property_name'],
                    'details'               => $input['details'],
                    'property_type'         => $input['property_type'],
                    /*'bathroom_no'           => $input['bathroom_no'],
                    'bedroom_no'            => $input['bedroom_no'],*/
                    'building_area'         => $input['building_area'],
                    'actual_area'           => $input['actual_area'],
                    'parking_no'            => $input['parking_no'],
                    'garage_no'             => $input['garage_no'],
                    'insurance_company'     => $input['insurance_company'],
                    'insurance_start_time'  => $input['insurance_start_time'],
                    'insurance_end_time'    => $input['insurance_end_time'],
                    'Region'                => $input['Region'],
                    'TA'                    => $input['TA'],
                    'District'              => $input['District'],
                    'address'               => $input['address'],
                    'lat'                   => $input['lat'],
                    'lon'                   => $input['lon'],
                    'short_words'           => implode(',',$input['short_words']),
                    /*'available_time'        => $input['available_time'],*/
                    'rent_period'           => $input['rent_period'],
                    'rent_fee'              => $input['rent_fee'],
                    'rent_least_fee'        => $input['rent_least_fee'],
                    'rent_fee_detail'       => $input['rent_fee_detail'],
                    'other_rule'            => $input['other_rule'],
                    'created_at'            => date('Y-m-d H:i:s',time()),
                ]; // 房屋主档数据
                $rent_house_id = $model->insertGetId($data); //获取房屋主档id
                // 添加图片
                $rent_pic = $input['house_pic'];
                static $error = 0;
                foreach ($rent_pic as $k => $v){
                    $pic_data = [
                        'rent_house_id' => $rent_house_id,
                        'house_pic'     => $v,
                        'created_at'    => date('Y-m-d H:i:s',time()),
                    ];
                    $res = RentPic::insert($pic_data);
                    if(!$res){
                        $error +=1;
                    }
                }
                // 添加联系人
                $contact_info = $input['contact_info'];
                foreach ($contact_info as $key => $value){
                    $contact_data = [
                        'rent_house_id' => $rent_house_id,
                        'contact_name'  => $value['contact_name'],
                        'contact_role'  => $value['contact_role'],
                        'e_mail'        => $value['e_mail'],
                        'phone'         => $value['phone'],
                        'created_at'    => date('Y-m-d H:i:s',time()),
                    ];
                    $res = RentContact::insert($contact_data);
                    if(!$res){
                        $error +=1;
                    }
                }
                if($rent_house_id && !$error){
                    return $this->success('rent_house_list add succcess');
                }else{
                    return $this->error('2','rent_house_list add failed, Pls try again');
                }
            }else{
                return $this->error('3','wrong rent_type');
            }
        }else{
            return $this->error('2','you are not a landlord pls build the house list after become a landlord');
        }
    }

    /**
     * @description:获得房屋主档列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHouseList(array $input)
    {
        $model = new RentHouse();
        $model = $model->where('is_put',2);
        $model = $model->where('rent_status',1);
        // 房屋主档类型筛选
        $rent_category = @$input['rent_category'];
        if($rent_category){
            $model = $model->where('rent_category',$rent_category);
        }
        // 地区筛选
        $region = @$input['Region'];
        $ta     = @$input['TA'];
        $district   = @$input['District'];
        if($district){
            $model = $model->where('District',$district);
        }elseif ($ta){
            $model = $model->where('TA',$ta);
        }elseif ($region){
            $model = $model->where('Region',$region);
        }
        // 卧室筛选
        $bedroom_least = @$input['bedroom_least'];
        $bedroom_most  = @$input['bedroom_most'];
        if($bedroom_least){
            $model = $model->where('bedroom_no','>=',$bedroom_least);
        }
        if($bedroom_most){
            $model = $model->where('bedroom_no','<=',$bedroom_most);
        }
        // 洗手间筛选
        $bathroom_least = @$input['bathroom_least'];
        $bathroom_most  = @$input['bathroom_most'];
        if($bathroom_least){
            $model = $model->where('bathroom_no','>=',$bathroom_least);
        }
        if($bathroom_most){
            $model = $model->where('bathroom_no','<=',$bathroom_most);
        }
        // 租金筛选
        $rent_fee_least = @$input['rent_fee_least'];
        $rent_fee_most  = @$input['rent_fee_most'];
        if($rent_fee_least){
            $model = $model->where('rent_fee_pre_week','>=',$rent_fee_least);
        }
        if($rent_fee_most){
            $model = $model->where('rent_fee_pre_week','>=',$rent_fee_least);
        }
        if($input['sort_order'] == 2){
            $model = $model->orderBy('rent_fee_pre_week','desc');
        }
        if($input['show_method'] == 1){ // 列表
            $offset = ($input['page']-1)*5;
            $count = $model->count();
            $total_page = ceil($count/5);
            $res = $model->offset($offset)->limit(5)->select('id','rent_category','property_name','property_type','address','available_time','rent_fee_pre_week','rent_least_fee','bedroom_no','bathroom_no','parking_no','garage_no','District','TA','Region','available_date','require_renter')->get()->toArray();
            foreach ($res as $k => $v){
                $res[$k]['house_pic'] = RentPic::where('rent_house_id',$v['id'])->where('deleted_at',null)->pluck('house_pic')->toArray();// 图片
                $res[$k]['full_address'] = $v['address'].','.Region::getName($v['District']).','.Region::getName($v['TA']).','.Region::getName($v['Region']); //地址
            }
            $data['house_info'] = $res;
            $data['total_page'] = $total_page;
            $data['current_page'] = $input['page'];
        }else{
            $offset = ($input['page']-1)*9;
            $count = $model->count();
            $total_page = ceil($count/9);
            $res = $model->offset($offset)->limit(9)->select('id','rent_category','property_name','property_type','address','available_time','rent_fee_pre_week','rent_least_fee','bedroom_no','bathroom_no','parking_no','garage_no','District','TA','Region','available_date','require_renter')->get()->toArray();
            foreach ($res as $k => $v){
                $res[$k]['house_pic'] = RentPic::where('rent_house_id',$v['id'])->where('deleted_at',null)->pluck('house_pic')->toArray();// 图片
                $res[$k]['full_address'] = $v['address'].','.Region::getName($v['District']).','.Region::getName($v['TA']).','.Region::getName($v['Region']);
            }
            $data['house_info'] = $res;
            $data['total_page'] = $total_page;
            $data['current_page'] = $input['page'];
        }
        if($res){
            return $this->success('rent_house_list get success',$data);
        }else{
            return $this->error('2','rent_house_list get failed');
        }
    }

    /**
     * @description:获得房屋主档信息
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function houseDetail(array $input)
    {
        $model = new RentHouse();
        $rent_house_id = $input['rent_house_id'];
        $res = $model->where('id',$rent_house_id)->select('group_id','property_name','rent_fee_pre_week','building_area','actual_area','pre_rent','least_rent_time','margin_rent','bedroom_no','bathroom_no','parking_no','garage_no','require_renter','short_words','rent_fee','rent_least_fee','can_party','can_pet','can_smoke','other_rule','address','lat','lon','available_date')->first()->toArray();
        if($res){
            $res['house_pic'] =  RentPic::where('rent_house_id',$rent_house_id)->where('deleted_at',null)->pluck('house_pic')->toArray();
            $res['short_words'] = explode(',',$res['short_words']);
            return $this->success('get house info success',$res);
        }else{
            return $this->error('2','get house info failed');
        }
    }

    /**
     * @description:房屋主档上架
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function houseListPut(array $input)
    {
        $model = new RentHouse();
        $group_id = $input['group_id'];
        $update_data = [
            'is_put'            => 2,
            'available_date'    => $input['available_date']
        ];
        $res = $model->where('group_id',$group_id)->update($update_data);
        /*$res->is_put = 2;
        $res->available_date = $input['available_date'];
        $res->save();*/
        return $this->success('put the house list success');
    }


    /**
     * @description:房屋主档下架
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function houseListDown(array $input)
    {
        $model = new RentHouse();
        $group_id = $input['group_id'];
        $update_data = [
            'is_put'            => 1,
            'available_date'    => $input['available_date']
        ];
        $res = $model->where('group_id',$group_id)->update($update_data);
        /*$res->is_put = 2;
        $res->available_date = $input['available_date'];
        $res->save();*/
        return $this->success('down the house list success');
    }

    /**
     * @description:编辑房屋主档
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function editHouseList(array $input)
    {
        $user_id = $input['user_id'];
        $rent_category = $input['rent_category'];
        $model = new RentHouse();
        $user_role = \App\Model\User::where('id',$user_id)->first()['user_role'];
        if($user_role == 1 || $user_role ==3 || $user_role == 5 || $user_role == 7){
            if($rent_category == 1){ // 编辑整租房屋主档
                $rent_house_info = $model->where('id',$input['rent_house_id'])->first();
                if($input['rent_period'] == 1){
                    $rent_fee_pre_week = $input['rent_fee']*7;
                }elseif ($input['rent_period'] == 2){
                    $rent_fee_pre_week = $input['rent_fee'];
                }elseif ($input['rent_period'] == 3){
                    $rent_fee_pre_week = $input['rent_fee']/4;
                }elseif ($input['rent_period'] == 4){
                    $rent_fee_pre_week = $input['rent_fee']/13;
                }elseif ($input['rent_period'] == 5){
                    $rent_fee_pre_week = $input['rent_fee']/26;
                }elseif ($input['rent_period'] == 6){
                    $rent_fee_pre_week = $input['rent_fee']/52;
                }
                $data = [
                    'property_name'         => @$input['property_name']?$input['property_name']:$rent_house_info->property_name,
                    'details'               => @$input['details']?$input['details']:$rent_house_info->details,
                    'property_type'         => @$input['property_type']?$input['property_type']:$rent_house_info->property_type,
                    'bathroom_no'           => @$input['bathroom_no']?$input['bathroom_no']:$rent_house_info->bathroom_no,
                    'bedroom_no'            => @$input['bedroom_no']?$input['bedroom_no']:$rent_house_info->bedroom_no,
                    'building_area'         => @$input['building_area']?$input['building_area']:$rent_house_info->building_area,
                    'actual_area'           => @$input['actual_area']?$input['actual_area']:$rent_house_info->actual_area,
                    'parking_no'            => @$input['parking_no']?$input['parking_no']:$rent_house_info->packing_no,
                    'garage_no'             => @$input['garage_no']?$input['garage_no']:$rent_house_info->garage_no,
                    'insurance_company'     => @$input['insurance_company']?$input['insurance_company']:$rent_house_info->insurance_company,
                    'insurance_start_time'  => @$input['insurance_start_time']?$input['insurance_start_time']:$rent_house_info->insurance_start_time,
                    'insurance_end_time'    => @$input['insurance_end_time']?$input['insurance_end_time']:$rent_house_info->insurance_end_time,
                    'Region'                => @$input['Region']?$input['Region']:$rent_house_info->Region,
                    'TA'                    => @$input['TA']?$input['TA']:$rent_house_info->TA,
                    'District'              => @$input['District']?$input['District']:$rent_house_info->District,
                    'address'               => @$input['address']?$input['address']:$rent_house_info->address,
                    'lat'                   => @$input['lat']?$input['lat']:$rent_house_info->lat,
                    'lon'                   => @$input['lon']?$input['lon']:$rent_house_info->lon,
                    'short_words'           => @$input['short_words']?implode(',',$input['short_words']):$rent_house_info->short_words,
                    'bus_station'           => @$input['bus_station']?$input['bus_station']:$rent_house_info->bus_station,
                    'school'                => @$input['school']?$input['school']:$rent_house_info->school,
                    'supermarket'           => @$input['supermarket']?$input['supermarket']:$rent_house_info->supermarket,
                    'hospital'              => @$input['hospital']?$input['hospital']:$rent_house_info->hospital,
                    'available_time'        => @$input['available_time']?$input['available_time']:$rent_house_info->available_time,
                    'rent_period'           => @$input['rent_period']?$input['rent_period']:$rent_house_info->rent_period,
                    'rent_fee'              => @$input['rent_fee']?$input['rent_fee']:$rent_house_info->rent_fee,
                    'rent_fee_pre_week'     => @$rent_fee_pre_week,
                    'least_rent_time'       => @$input['least_rent_time']?$input['least_rent_time']:$rent_house_info->least_rent_time,
                    'least_rent_method'     => @$input['least_rent_method']?$input['least_rent_method']:$rent_house_info->least_rent_method,
                    'pre_rent'              => @$input['pre_rent']?$input['pre_rent']:$rent_house_info->pre_rent,
                    'pre_rent_fee'          => @$input['pre_rent_fee']?$input['pre_rent_fee']:$rent_house_info->pre_rent_fee,
                    'margin_rent'           => @$input['margin_rent']?$input['margin_rent']:$rent_house_info->margin_rent,
                    'margin_rent_fee'       => @$input['margin_rent_fee']?$input['margin_rent_fee']:$rent_house_info->margin_rent_fee,
                    'total_need_fee'        => @$input['total_need_fee']?$input['total_need_fee']:$rent_house_info->total_rent_fee,
                    'can_party'             => @$input['can_party']?$input['can_party']:$rent_house_info->can_party,
                    'can_pet'               => @$input['can_pet']?$input['can_pet']:$rent_house_info->cna_pet,
                    'can_smoke'             => @$input['can_smoke']?$input['can_smoke']:$rent_house_info->can_smoke,
                    'other_rule'            => @$input['other_rule']?$input['other_rule']:$rent_house_info->other_rule,
                    'is_put'                => 1,
                    'updated_at'            => date('Y-m-d H:i:s',time()),
                ]; // 房屋主档数据
                $res = $model->where('id',$input['rent_house_id'])->update($data); //获取房屋主档id
                // 删除之前图片
                RentPic::where('rent_house_id',$input['rent_house_id'])->update('deleted_at',date('Y-m-d H:i:s',time()));
                // 添加图片
                $rent_pic = $input['house_pic'];
                static $error = 0;
                foreach ($rent_pic as $k => $v){
                    $pic_data = [
                        'rent_house_id' => $input['rent_house_id'],
                        'house_pic'     => $v,
                        'created_at'    => date('Y-m-d H:i:s',time()),
                    ];
                    $res = RentPic::insert($pic_data);
                    if(!$res){
                        $error +=1;
                    }
                }
                // 添加联系人
                // 删除之前联系人
                RentContact::where('rent_house_id',$input['rent_house_id'])->update('deleted_at',date('Y-m-d H:i:s',time()));
                $contact_info = $input['contact_info'];
                foreach ($contact_info as $key => $value){
                    $contact_data = [
                        'rent_house_id' => $input['rent_house_id'],
                        'contact_name'  => $value['contact_name'],
                        'contact_role'  => $value['contact_role'],
                        'e_mail'        => $value['e_mail'],
                        'phone'         => $value['phone'],
                        'created_at'    => date('Y-m-d H:i:s',time()),
                    ];
                    $res = RentContact::insert($contact_data);
                    if(!$res){
                        $error +=1;
                    }
                }
                if($res && !$error){
                    return $this->success('rent_house_list edit succcess');
                }else{
                    return $this->error('2','rent_house_list edit failed, Pls try again');
                }
            }elseif ($rent_category == 2 || $rent_category == 3){ // 新建分租/室友房屋主档
                $rent_house_id = $input['rent_house_id'];
                static $error = 0;
                foreach ($rent_house_id as $k => $v){
                    $rent_house_info = $model->where('id',$v)->first();
                    if($input['rent_period'] == 1){
                        $rent_fee_pre_week = $input['rent_fee'][$k]*7;
                    }elseif ($input['rent_period'] == 2){
                        $rent_fee_pre_week = $input['rent_fee'][$k];
                    }elseif ($input['rent_period'] == 3){
                        $rent_fee_pre_week = $input['rent_fee'][$k]/4;
                    }elseif ($input['rent_period'] == 4){
                        $rent_fee_pre_week = $input['rent_fee'][$k]/13;
                    }elseif ($input['rent_period'] == 5){
                        $rent_fee_pre_week = $input['rent_fee'][$k]/26;
                    }elseif ($input['rent_period'] == 6){
                        $rent_fee_pre_week = $input['rent_fee'][$k]/52;
                    }
                    $data = [
                        'property_name'         => @$input['property_name']?$input['property_name']:$rent_house_info->property_name,
                        'details'               => @$input['details']?$input['details']:$rent_house_info->details,
                        'property_type'         => @$input['property_type']?$input['property_type']:$rent_house_info->property_type,
                        'bathroom_no'           => @$input['bathroom_no']?$input['bathroom_no']:$rent_house_info->bathroom_no,
                        'bedroom_no'            => @$input['bedroom_no']?$input['bedroom_no']:$rent_house_info->bedroom_no,
                        'building_area'         => @$input['building_area']?$input['building_area']:$rent_house_info->building_area,
                        'actual_area'           => @$input['actual_area']?$input['actual_area']:$rent_house_info->actual_area,
                        'parking_no'            => @$input['parking_no']?$input['parking_no']:$rent_house_info->packing_no,
                        'garage_no'             => @$input['garage_no']?$input['garage_no']:$rent_house_info->garage_no,
                        'insurance_company'     => @$input['insurance_company']?$input['insurance_company']:$rent_house_info->insurance_company,
                        'insurance_start_time'  => @$input['insurance_start_time']?$input['insurance_start_time']:$rent_house_info->insurance_start_time,
                        'insurance_end_time'    => @$input['insurance_end_time']?$input['insurance_end_time']:$rent_house_info->insurance_end_time,
                        'Region'                => @$input['Region']?$input['Region']:$rent_house_info->Region,
                        'TA'                    => @$input['TA']?$input['TA']:$rent_house_info->TA,
                        'District'              => @$input['District']?$input['District']:$rent_house_info->District,
                        'address'               => @$input['address']?$input['address']:$rent_house_info->address,
                        'lat'                   => @$input['lat']?$input['lat']:$rent_house_info->lat,
                        'lon'                   => @$input['lon']?$input['lon']:$rent_house_info->lon,
                        'short_words'           => @$input['short_words']?implode(',',$input['short_words']):$rent_house_info->short_words,
                        'bus_station'           => @$input['bus_station']?$input['bus_station']:$rent_house_info->bus_station,
                        'school'                => @$input['school']?$input['school']:$rent_house_info->school,
                        'supermarket'           => @$input['supermarket']?$input['supermarket']:$rent_house_info->supermarket,
                        'hospital'              => @$input['hospital']?$input['hospital']:$rent_house_info->hospital,
                        'available_time'        => @$input['available_time']?$input['available_time']:$rent_house_info->available_time,
                        'room_name'             => @$input['room_name'][$k]?$input['room_name'][$k]:$rent_house_info->room_name,
                        'room_description'      => @$input['room_description'][$k]?$input['room_description'][$k]:$rent_house_info->room_description,
                        'bed_no'                => @$input['bed_no'][$k]?$input['room_description'][$k]:$rent_house_info->room_description,
                        'shower_room'           => @$input['shower_room'][$k]?$input['shower_room'][$k]:$rent_house_info->shower_room,
                        'require_renter'        => @$input['require_renter'][$k]?$input['require_renter'][$k]:$rent_house_info->require_renter,
                        'room_short_words'      => @$input['room_short_words'][$k]?implode(',',$input['room_short_words'][$k]):$rent_house_info->room_short_words,
                        'rent_period'           => @$input['rent_period'][$k]?$input['rent_period'][$k]:$rent_house_info->rent_period,
                        'rent_fee'              => @$input['rent_fee'][$k]?$input['rent_fee'][$k]:$rent_house_info->rent_fee,
                        'rent_fee_pre_week'     => @$rent_fee_pre_week,
                        'least_rent_time'       => @$input['least_rent_time'][$k]?$input['least_rent_time'][$k]:$rent_house_info->least_rent_time,
                        'least_rent_method'     => @$input['least_rent_method'][$k]?$input['least_rent_method'][$k]:$rent_house_info->least_rent_method,
                        'pre_rent'              => @$input['pre_rent'][$k]?$input['pre_rent'][$k]:$rent_house_info->pre_rent,
                        'pre_rent_fee'          => @$input['pre_rent_fee'][$k]?$input['pre_rent_fee'][$k]:$rent_house_info->pre_rent_fee,
                        'margin_rent'           => @$input['margin_rent'][$k]?$input['margin_rent'][$k]:$rent_house_info->margin_rent,
                        'margin_rent_fee'       => @$input['margin_rent_fee'][$k]?$input['margin_rent_fee'][$k]:$rent_house_info->margin_rent_fee,
                        'total_need_fee'        => @$input['total_need_fee'][$k]?$input['total_need_fee'][$k]:$rent_house_info->total_need_fee,
                        'other_rule'            => @$input['other_rule']?$input['other_rule']:$rent_house_info->other_rule,
                        'is_put'                => 1,
                        'updated_at'            => date('Y-m-d H:i:s',time()),
                    ]; // 房屋主档数据
                    $res = $model->where('id',$v)->update($data); //获取房屋主档id
                    if(!$res){ // 没有修改主档成功
                        $error += 1;
                    }else{
                        // 删除之前图片
                        RentPic::where('rent_house_id',$v)->update('deleted_at',date('Y-m-d H:i:s',time()));
                        // 添加图片
                        $rent_pic = $input['house_pic'][$k];
                        foreach ($rent_pic as $key=> $value){
                            $pic_data = [
                                'rent_house_id' => $v,
                                'house_pic'     => $value,
                                'created_at'    => date('Y-m-d H:i:s',time()),
                            ];
                            $res = RentPic::insert($pic_data);
                            if(!$res){ // 没有添加图片成功
                                $error +=1;
                            }
                        }
                        // 删除之前联系人
                        RentContact::where('rent_house_id',$v)->update('deleted_at',date('Y-m-d H:i:s',time()));
                        // 添加联系人
                        $contact_info = $input['contact_info'];
                        foreach ($contact_info as $key => $value){
                            $contact_data = [
                                'rent_house_id' => $input['rent_house_id'],
                                'contact_name'  => $value['contact_name'],
                                'contact_role'  => $value['contact_role'],
                                'e_mail'        => $value['e_mail'],
                                'phone'         => $value['phone'],
                                'created_at'    => date('Y-m-d H:i:s',time()),
                            ];
                            $res = RentContact::insert($contact_data);
                            if(!$res){
                                $error +=1;
                            }
                        }
                    }

                }
                if(!$error){
                    return $this->success('rent_house_list edit succcess');
                }else{
                    return $this->error('2','rent_house_list edit failed, Pls try again');
                }

            }elseif ($rent_category == 4){ //修改商业房屋主档
                $rent_house_info = $model->where('id',$input['rent_house_id'])->first();
                $data = [
                    'property_name'         => @$input['property_name']?$input['property_name']:$rent_house_info->property_name,
                    'details'               => @$input['details']?$input['details']:$rent_house_info->details,
                    'property_type'         => @$input['property_type']?$input['property_type']:$rent_house_info->property_type,
                   /* 'bathroom_no'           => @$input['bathroom_no']?$input['bathroom_no']:$rent_house_info->bathroom_no,
                    'bedroom_no'            => @$input['bedroom_no']?$input['bedroom_no']:$rent_house_info->bedroom_no,*/
                    'building_area'         => @$input['building_area']?$input['building_area']:$rent_house_info->building_area,
                    'actual_area'           => @$input['actual_area']?$input['actual_area']:$rent_house_info->actual_area,
                    'parking_no'            => @$input['parking_no']?$input['parking_no']:$rent_house_info->packing_no,
                    'garage_no'             => @$input['garage_no']?$input['garage_no']:$rent_house_info->garage_no,
                    'insurance_company'     => @$input['insurance_company']?$input['insurance_company']:$rent_house_info->insurance_company,
                    'insurance_start_time'  => @$input['insurance_start_time']?$input['insurance_start_time']:$rent_house_info->insurance_start_time,
                    'insurance_end_time'    => @$input['insurance_end_time']?$input['insurance_end_time']:$rent_house_info->insurance_end_time,
                    'Region'                => @$input['Region']?$input['Region']:$rent_house_info->Region,
                    'TA'                    => @$input['TA']?$input['TA']:$rent_house_info->TA,
                    'District'              => @$input['District']?$input['District']:$rent_house_info->District,
                    'address'               => @$input['address']?$input['address']:$rent_house_info->address,
                    'lat'                   => @$input['lat']?$input['lat']:$rent_house_info->lat,
                    'lon'                   => @$input['lon']?$input['lon']:$rent_house_info->lon,
                    'short_words'           => @$input['short_words']?implode(',',$input['short_words']):$rent_house_info->short_words,
                    'available_time'        => @$input['available_time']?$input['available_time']:$rent_house_info->available_time,
                    'rent_period'           => @$input['rent_period']?$input['rent_period']:$rent_house_info->rent_period,
                    'rent_fee'              => @$input['rent_fee']?$input['rent_fee']:$rent_house_info->rent_fee,
                    'rent_least_fee'        => @$input['rent_least_fee']?$input['rent_least_fee']:$rent_house_info->rent_least_fee,
                    'rent_fee_detail'       => @$input['rent_fee_detail']?$input['rent_fee_detail']:$rent_house_info->rent_fee_detail,
                    'other_rule'            => @$input['other_rule']?$input['other_rule']:$rent_house_info->other_rule,
                    'is_put'                => 1,
                    'updated_at'            => date('Y-m-d H:i:s',time()),
                ]; // 房屋主档数据
                $res = $model->where('id',$input['rent_house_id'])->update($data); //获取房屋主档id
                // 删除之前图片
                RentPic::where('rent_house_id',$input['rent_house_id'])->update('deleted_at',date('Y-m-d H:i:s',time()));
                // 添加图片
                $rent_pic = $input['house_pic'];
                static $error = 0;
                foreach ($rent_pic as $k => $v){
                    $pic_data = [
                        'rent_house_id' => $input['rent_house_id'],
                        'house_pic'     => $v,
                        'created_at'    => date('Y-m-d H:i:s',time()),
                    ];
                    $res = RentPic::insert($pic_data);
                    if(!$res){
                        $error +=1;
                    }
                }
                // 添加联系人
                // 删除之前联系人
                RentContact::where('rent_house_id',$input['rent_house_id'])->update('deleted_at',date('Y-m-d H:i:s',time()));
                $contact_info = $input['contact_info'];
                foreach ($contact_info as $key => $value){
                    $contact_data = [
                        'rent_house_id' => $input['rent_house_id'],
                        'contact_name'  => $value['contact_name'],
                        'contact_role'  => $value['contact_role'],
                        'e_mail'        => $value['e_mail'],
                        'phone'         => $value['phone'],
                        'created_at'    => date('Y-m-d H:i:s',time()),
                    ];
                    $res = RentContact::insert($contact_data);
                    if(!$res){
                        $error +=1;
                    }
                }
                if($res && !$error){
                    return $this->success('rent_house_list edit succcess');
                }else{
                    return $this->error('2','rent_house_list edit failed, Pls try again');
                }
            }else{
                return $this->error('3','wrong rent_type');
            }
        }else{
            return $this->error('2','you are not a landlord pls build the house list after become a landlord');
        }
    }



    /**
     * @description:获得房屋主档具体信息
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHouseInfomation(array $input)
    {
        $model = new RentHouse();
        $rent_house_id = $input['rent_house_id'];
        $res = $model->where('id',$rent_house_id)->select('group_id','property_name','rent_fee_pre_week','building_area','actual_area','pre_rent','least_rent_time','margin_rent','bedroom_no','bathroom_no','parking_no','garage_no','require_renter','short_words','rent_fee','rent_least_fee','can_party','can_pet','can_smoke','other_rule','address','lat','lon','available_date')->get();
        if($res){
            $res['house_pic'] =  RentPic::where('rent_house_id',$rent_house_id)->where('deleted_at',null)->pluck('house_pic')->toArray();
            $res['short_word'] = explode(',',$res['short_word']);
            return $this->success('get house info success',$res);
        }else{
            return $this->error('2','get house info failed');
        }
    }

    /**
     * @description:获得房屋主档信息列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSelfHouseList(array $input)
    {
        $model = new RentHouse();
        $user_id = $input['user_id'];
        $page = $input['page'];
        $count = $model->groupBy('group_id')->count();
        if($count < ($page-1)*9){
            return $this->error('3','the page number is not right');
        }
        $res = $model->where('user_id',$user_id)->select('id','group_id','rent_category','property_name','property_type','rent_fee_pre_week','building_area','actual_area','pre_rent','least_rent_time','margin_rent','bedroom_no','bathroom_no','parking_no','garage_no','require_renter','short_words','rent_fee','rent_least_fee','can_party','can_pet','can_smoke','other_rule','address','lat','lon','available_date','is_put')->groupBy('group_id')->offset(($page-1)*9)->limit(9)->get();
        if($res){
            foreach ($res as $k => $v){
                $res[$k]['house_pic'] =  RentPic::where('rent_house_id',$v['id'])->where('deleted_at',null)->pluck('house_pic')->toArray();
            }
            $data['house_list'] = $res;
            $data['current_page'] = $page;
            $total_page = ceil($count/9);
            $data['total_page'] = $total_page;
            return $this->success('get house list success',$data);
        }else{
            return $this->error('2','get house list failed');
        }
    }

    /**
     * @description:获得房屋主档信息列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHouseGroupDetail(array $input)
    {
        $model = new RentHouse();
        $user_id = $input['user_id'];
        $group_id = $input['group_id'];
        $res = $model->where('user_id',$user_id)->where('group_id',$group_id)->get()->toArray();
        if($res){
            foreach ($res as $k => $v){
                $res[$k]['house_pic'] =  RentPic::where('rent_house_id',$v['id'])->where('deleted_At',null)->pluck('house_pic')->toArray();
            }
            return $this->success('get house info success',$res);
        }else{
            return $this->error('2','get house list failed');
        }
    }


    /**
     * @description:删除房屋主档
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteHouseList(array $input)
    {
        $model = new RentHouse();
        $user_id = $input['user_id'];
        $group_id = $input['group_id'];
        $res = $model->where('user_id',$user_id)->where('group_id',$group_id)->get()->toArray();
        if($res){
            foreach ($res as $k => $v){
                RentPic::where('rent_house_id',$v['id'])->update('deleted_at',date('Y-m-d H:i:s',time()));
            }
            $model->where('user_id',$user_id)->where('group_id',$group_id)->update('deleted_at',date('Y-m-d H:i:s',time()));
            return $this->success('delete house info success',$res);
        }else{
            return $this->error('2','delete house list failed');
        }
    }
}