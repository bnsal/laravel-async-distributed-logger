<?php

namespace Bnsal\DistributedLogger;

use Illuminate\Http\Request;

use Log;

class DistributedLoggingController
{
    
    public $object = null;

    public $should_dump = false;

    public function __construct() {
        $this->object = [
            "url" => request()->fullUrl(),
            "method_type" => @$_SERVER['REQUEST_METHOD'],
            "user_agent" => @$_SERVER['HTTP_USER_AGENT'],
            "refer" => @$_SERVER['HTTP_REFERER'],
            "clientIp:" => @$this->clientIp(),
            "machineIp" => @$_SERVER['REMOTE_ADDR'],
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
        if( request()->isMethod('post') || ($response && isset($response->headers) && $response->headers && $response->headers->header('Content-type') && stripos($response->headers->header('Content-type'), "json")) ) {
            $this->should_dump = true;
        }

        $this->object['data']['response'] = $response->getContent();
        $this->object['response_status_code'] = $response->status();
        $this->object['data']['response_at'] = intval(microtime(true) * 1000);
    }

    public function addLogEntry($record) {
        if( $record['level'] >= 400 ) {
            $this->should_dump = true;
        }
        $this->object['logs'][] = $record['message'];
    }

    public function isDumpable() {
        return $this->should_dump;
    }

    public function dump() {
        return $this->object;
    }

}
