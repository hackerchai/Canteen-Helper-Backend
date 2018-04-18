<?php
namespace app\student\controller;
use app\lib\validate\CodeValidate;
use app\service\Token as Toke;
class Token {
    public function get($code='')
    {
        (new CodeValidate())->goCheck();
        $ut=new Toke($code,"user",'wx_canteen_helper');
        $token=$ut->get();
        return $token;   
    }
}