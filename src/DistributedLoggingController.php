<?php

namespace Bnsal\DistributedLogger;

use Illuminate\Http\Request;

use Log;

class DistributedLoggingController
{
    
    public $object = null;

    public $should_dump = false;

    public $should_pretty_print = false;

    public function __construct() {
        $this->object = [
            "url" => request()->fullUrl(),
            "method_type" => @$_SERVER['REQUEST_METHOD'],
            "user_agent" => @$_SERVER['HTTP_USER_AGENT'],
            "refer" => @$_SERVER['HTTP_REFERER'],
            "clientIp:" => @$this->clientIp(),
            "machineIp" => @$this->privateIp(),
            "response_status_code" => null,


            "data" => [
                "request_hashes" => [],
                "request" => json_encode(request()->all()),
                "response" => null,
                "requeat_at" => intval(microtime(true) * 1000),
                "response_at" => null
            ],

            "logs" => []
        ];
    }

    function clientIp() {
        if( isset($_SERVER['HTTP_CF_CONNECTING_IP']) && $_SERVER['HTTP_CF_CONNECTING_IP'] ) {
            return $_SERVER['HTTP_CF_CONNECTING_IP'];
        }
        return request()->ip();
    }

    public function setResponse($response) {

        if( request()->isMethod('post') && $response && $response instanceof Illuminate\Http\Response && isset($response->headers) && $response->headers && $response->headers->header('Content-type') && !stripos($response->headers->header('Content-type'), "html") ) {
            $this->object['data']['response'] = $response->getContent();
        }

        if( $response && $response instanceof Illuminate\Http\Response && isset($response->headers) && $response->headers && $response->headers->header('Content-type') && stripos($response->headers->header('Content-type'), "json") ) {
            $this->object['data']['response'] = $response->getContent();
        }

        $this->object['response_status_code'] = $response->status();
        $this->object['data']['response_at'] = intval(microtime(true) * 1000);
    }

    public function addLogEntry($record) {
        if( $record['level'] >= 400 ) {
            $this->should_dump = true;
        }

        $msg = [];
        $msg['message'] = $record['message'];
        if( isset($record['context']) && isset($record['context']['exception']) && $record['context']['exception'] ) {
            $this->should_pretty_print = true;
            $exception = $record['context']['exception'];
            $msg['exception'] = sprintf(
                "Uncaught exception '%s' with message '%s' in %s:%d",
                get_class($exception),
                $exception->getMessage(),
                $exception->getTrace()[0]['file'],
                $exception->getTrace()[0]['line']
            );
        }

        $this->object['logs'][] = $msg;
    }

    public function isDumpable() {
        return $this->should_dump;
    }

    public function isPrettyPrint() {
        return $this->should_pretty_print;
    }


    public function privateIp() {
        $str = str_replace('-', '.', str_replace( 'ip-', '', gethostname() ) );
        if( substr_count( $str, "." ) > 3 ) {
            $parts = explode(".", $str);
            $str = $parts[0] . '.' . $parts[1] . '.' . $parts[2] . '.' . $parts[3];
        }
        return $str;
    }

    public function dump() {
        return $this->object;
    }

}
