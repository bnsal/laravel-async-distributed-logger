<?php

namespace Bnsal\DistributedLogger;

use Closure;
use Illuminate\Http\Request;
use Bnsal\DistributedLogger\DistributedLoggingQueueJob;
use App;

class DistributedLoggingMiddleware {

    public $log;

    public function handle(Request $request, Closure $next) {
        return $next($request);
    }

    public function terminate($request, $response) {
        $distributedLoggingController = App::make(DistributedLoggingController::class);
        $distributedLoggingController->setResponse($response);
        $json = json_encode($distributedLoggingController->dump());

        if( config('bnsallogging.queue_enabled') ) {
            if( config('bnsallogging.queue_driver') ) {
                DistributedLoggingQueueJob::dispatch($json)->onConnection( config('bnsallogging.queue_driver') );
            } else {
                DistributedLoggingQueueJob::dispatch($json);
            }
        }

        \Log::channel( config('bnsallogging.logging_channel', 'single') )->info($json);
    }
}