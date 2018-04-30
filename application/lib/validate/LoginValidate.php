<?php
namespace app\lib\validate;
class LoginValidate extends BaseValidate{
    protected $rule=[
        ["adminName" ,'require|length:4,10','你还没有填写用户名^_^ | 用户名长度必须在4到10之间'],
        ["password"  ,'require|length:6,16', "您还没有填写密码^_^  | 密码的长度必须在6到16之间"],
        ['captcha' , 'require|captcha',"验证码还没有填写^_^|验证码错误"],
    ];
   
}