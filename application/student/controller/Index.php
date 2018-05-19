<?php
namespace app\student\controller;
use app\lib\qrcode\Qcode;
use app\lib\sms\Sms;
use app\lib\jwc\Jwc;
class Index
{
    public function index()
    {
        // var_dump(Qcode::png("http://www.baidu.com"));
        // var_dump(Qcode::getName());
        // var_dump(Qcode::getRelativeName());
        // $sms=new Sms();
        // $res=$sms->sendSms("17725029601");
        // return $this->succeed($res);

        // $param=input("param.");
        // var_dump($param);
        // return curl_post(config("jwc.url"),$param);
        $jwc=new Jwc(input("param."));
        $jwc->getName();
       
    }
}
