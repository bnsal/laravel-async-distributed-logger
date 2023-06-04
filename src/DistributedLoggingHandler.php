<?php

namespace Bnsal\DistributedLogger;

use Illuminate\Http\Request;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Handler\AbstractHandler;
use Log;
use Monolog\Logger;

use App;


class DistributedLoggingHandler extends AbstractProcessingHandler {
    
    public function __construct($level = Logger::DEBUG) {
        parent::__construct($level);
    }

    protected function write(array $record): void {
        $distributedLoggingController = App::make(DistributedLoggingController::class);
        $distributedLoggingController->addLogEntry($record);
    }

}