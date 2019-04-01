<?php

namespace App\Jobs;

use App\Model\Test;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class PushMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new job instance.
     * @param $i
     */
    public function __construct($i)
    {
        $this->user = $i;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //service('Shop')->test($this->user);
        Test::create(['content'=>'number:'.$this->user]);
    }
}
