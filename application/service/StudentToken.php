<?php
namespace app\service;
use app\lib\exception\WeChatException;
use Exception;
use app\student\model\User;
use app\lib\exception\TokenException;
class UserToken extends Token{
    protected $code;
    protected $appId;
    protected $secert;
    protected $loginUrl;
   
    public function __construct($code,$config=[])
    {
        if(empty($config))
        {
            $wx=config('wx');
        }
        else{
            $wx=$config;
        }
        $this->code=$code;
        $this->appId=$wx['appId'];
        $this->secert=$wx['appSecret'];     
        $this->loginUrl=sprintf($wx['loginUrl'],$this->appId,$this->secert,$this->code);
    }
    
}
