<?php

namespace App\Jobs;

use App\Model\Passport;
use App\Model\WxTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class WxTemplatePush implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $action;
    protected $passport;
    protected $data;
    protected $url;

    /**
     * Create a new job instance.
     *
     * @param $action
     * @param Passport $passport
     * @param $data
     * @param string $url
     * @internal param $touser
     * @internal param $template_id
     */
    public function __construct($action, Passport $passport, $data, $url = '')
    {
        $this->action = $action;
        $this->passport = $passport;
        $this->data = $data;
        $this->url = $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(in_array($this->action,array_keys(WxTemplate::$TEMPLATE))){
            $template_id = WxTemplate::$TEMPLATE[$this->action];
            $params = WxTemplate::$PARAMS[$this->action];
            foreach($params as $param){
                if(!@$this->data[$param]){
                    Log::info($param.'参数缺失');
                    return;
                }
            }
            $wechat = app('wechat');
            $wechat->notice->send([
                'touser' => $this->passport->openid,
                'template_id' => $template_id,
                'url' => $this->url,
                'data' => $this->data,
            ]);
        }else{
            Log::info('action参数错误');
        }
    }
}
