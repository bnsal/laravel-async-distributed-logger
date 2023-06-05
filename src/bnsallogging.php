<?php

use Monolog\Logger;

/*
    Author Name: Keshav Bansal
    Author Official Email: Keshav@bnsal.com
    Author Personal Email: keshavbansal0395@gmail.com
*/

return [

    'queue_enabled' => false,

    'queue_driver' => null,

    'disable_default_logging' => false,

    'logging_channel' => ['stack'],

    'log_level' => Logger::DEBUG,

    'log_print_level_index' => 10,

];
