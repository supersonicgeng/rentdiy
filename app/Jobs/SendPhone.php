<?php

namespace App\Jobs;

use App\Model\Config;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class SendPhone implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $phone;
    protected $send_action;
    protected $param;

    /**
     * Create a new job instance.
     *
     * @param $phone : 手机号
     * @param $send_action :发送行为
     * @param array $param : 发送参数
     */
    public function __construct($phone,$send_action,array $param)
    {
        $this->phone = $phone;
        $this->send_action = $send_action;
        $this->param = $param;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        service('AliSms')->sendTemplate($this->phone,$this->send_action,$this->param);
    }
}
