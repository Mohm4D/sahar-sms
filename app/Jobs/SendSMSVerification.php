<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class SendSMSVerification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $mobile;
    protected $code;

    public function __construct($mobile,$code)
    {
        $this->mobile=$mobile;
        $this->code=$code;
    }

    public function handle()
    {
        Log::info("Send " . $this->code . " as verify code to " . $this->mobile);
    }

}
