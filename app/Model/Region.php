<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Region extends Model
{
    protected $table = ['region'];
    protected $fillable = ['id','region_number','super_number','region_name','level'];
    public $timestamps = false;

    public static function getRegion()
    {
        $res = DB::table('region')->where('level',1)->get(['region_name','region_number','super_number']);
        return $res;
    }


    public static function getName($region_number)
    {
        $res = DB::table('region')->where('region_number',$region_number)->first();
        return $res->region_name;
    }
   /* public $timestamps = false;
    protected $fillable = ['id','name','parent_id','short_name','level','city_code','zip_code','merger_name','lng','lat','full_pinyin','pinyin'];

    public static function shortName($id){
        return Region::where('id',$id)->value('short_name');
    }

    public static function name($id){
        return Region::where('id',$id)->value('name');
    }

    public static function provinceId($name){
        $id1 = Region::where('level',1)->where('name',$name)->value('id');
        if($id1){
            return $id1;
        }
        $id2 = Region::where('level',1)->where('short_name',$name)->value('id');
        if($id2){
            return $id2;
        }
        return 0;
    }

    public static function cityId($province_id,$name){
        $id1 = Region::where('level',2)->where('parent_id',$province_id)->where('name',$name)->value('id');
        if($id1){
            return $id1;
        }
        $id2 = Region::where('level',2)->where('parent_id',$province_id)->where('short_name',$name)->value('id');
        if($id2){
            return $id2;
        }
        return 0;
    }

    public static function countyId($city_id,$name){
        $id1 = Region::where('level',3)->where('parent_id',$city_id)->where('name',$name)->value('id');
        if($id1){
            return $id1;
        }
        $id2 = Region::where('level',3)->where('parent_id',$city_id)->where('short_name',$name)->value('id');
        if($id2){
            return $id2;
        }
        return 0;
    }*/
}
