<?php
namespace app\lib\exception;
class PhoneException extends BaseException
{
    
    public $msg = '手机号码错误';
    public $errorCode = 407;
}