<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2018.5.14
 * Time: 下午 10:31
 */

namespace app\deliever\controller;


use app\lib\validate\CodeValidate;
use app\service\Token;

class Member extends BaseController
{
        public function login(){
            $cv=new CodeValidate();
            $param=$cv->goCheck();
            $code=$param["code"];
            $token=new Token($code, "student", "wx.s");
            $token=$token->get();
            return $this->succeed($token);
        }
}