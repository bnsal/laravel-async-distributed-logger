<?php

namespace Bnsal\DistributedLogger;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Bnsal\DistributedLogger\DistributedLoggingController;

use Log;

class DistributedLoggingQueueJob implements ShouldQueue {

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($log) {
        $this->data = $log;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        Log::emergency($this->data);
    }
}
