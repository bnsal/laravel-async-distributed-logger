<?php

use Monolog\Logger;

/*
    Author Name: Keshav Bansal
    Author Official Email: Keshav@bnsal.com
    Author Personal Email: keshavbansal0395@gmail.com
*/

return [

    //provides async logging feature
    'queue_enabled' => false,

    //set any queue driver, your app supports. for eg: {sqs, redis, beanstalkd, database, sync}
    'queue_driver' => null,

    //disable default logging
    'disable_default_logging' => true,

    //Log level, equal or above level logs will be recorded
    'log_level' => Logger::DEBUG,

    //control the log catch level, change it only if you understand what it is for
    'log_print_level_index' => 10,

    //pretty print the log file or compressed json entries are logged
    'pretty_print' => true,

    //add events where you want to capture the event data in your log pipelines
    'eventsToLog' => [
        \Illuminate\Console\Events\ScheduledTaskFinished::class,
        \Illuminate\Console\Events\ScheduledTaskFailed::class,
    ],


    //will be used only if log queue is disabled
    //set any additional logging channel your app supports. for eg: {null, stack, single, daily, slack, cloudwatch}
    'logging_channel' => ['stack'],

];
