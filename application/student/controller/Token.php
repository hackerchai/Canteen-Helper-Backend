<?php
namespace app\student\controller;
use app\lib\validate\CodeValidate;
use app\service\UserToken;
class Token {
    public function get($code='')
    {
        (new CodeValidate())->goCheck();
        $ut=new UserToken($code);
        $token=$ut->get();
        return 
        [
            "token" => $token
        ];
       
    }
}