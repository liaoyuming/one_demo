<?php

namespace App\Http\Channel;
// use App\Http\Channel\SmsYunpianAdapter;

class SmsAdapter
{

    private $sms_adapter = [
        "yunpian"  => "\App\Http\Channel\SmsYunpianAdapter",
    ];
    private $sms_obj;

    public function __construct($type, $sms_params = array())
    {
        $sms_adapter = $this->sms_adapter[$type];
        if (!$sms_adapter) {
            $sms_adapter = $this->sms_adapter['yunpian'];
        }

       $this->sms_obj = new $sms_adapter($type, $sms_params);
    }

    private function getAdapter($type, $sms_params)
    {
        $sms_adapter = $this->sms_adapter[$type];
        if (!$sms_adapter) {
            $sms_adapter = $this->sms_adapter['yunpian'];
        }
        new $sms_adapter($type, $sms_params);
    }

    public function send($mobile, $content)
    {
        $result = $this->sms_obj->send($mobile, $content);
        return $result;
    }

}
