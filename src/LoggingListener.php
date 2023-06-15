<?php

namespace Bnsal\DistributedLogger;

class LoggingListener {
	
	public function handle($event){
		app(\Bnsal\DistributedLogger\DistributedLoggingController::class)->setEventData($event);
		app(\Bnsal\DistributedLogger\DistributedLoggingMiddleware::class)->terminate(request(), response());
	}


}
