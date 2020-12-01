<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\Notification\FcmService;

class NotificationService implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $serviceMethod;

    protected $methodParams;

    /**
     * Create a new job instance.
     *
     * @param $serviceMethod
     * @param array $data
     */
    public function __construct($serviceMethod, $methodParams = [[]])
    {
        $this->serviceMethod = $serviceMethod;
        $this->methodParams = $methodParams;
    }

    /**
     * Execute the job.
     *
     * @param FcmService $fcmService
     * @return void
     */
    public function handle(FcmService $fcmService)
    {
        call_user_func_array(
            [
                $fcmService,
                $this->serviceMethod,
            ],
            $this->methodParams
        );
    }
}
