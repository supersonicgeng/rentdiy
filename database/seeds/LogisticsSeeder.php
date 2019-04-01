<?php

use App\Logistics;
use Illuminate\Database\Seeder;

class LogisticsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->logisticsSync();
    }

    private function logisticsSync(){
        $company_info = file_get_contents("http://www.kuaidi100.com/frame/index.html");
        $preg="/<a(.*)data-code=\"(.*)\"(.*)>[\s\S]*<span>(.*)<\/span>[\s\S]*<\/a>/iU";
        preg_match_all($preg,$company_info,$arr);
        $logistics = new Logistics();
        for($i=0,$count=count($arr[1]);$i<$count;$i++){
            //验证是否存在
            $code = $arr[2][$i];
            $r = $logistics->where(['code'=>$code])->first();
            if(!$r){
                $logistics->insert(['name'=>$arr[4][$i],'code'=>$arr[2][$i]]);
                echo $arr[4][$i]."数据导入完毕\n";
            }
        }
    }
}
