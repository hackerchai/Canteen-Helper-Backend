<?php
namespace app\lib\exception;

/**
 * 404时抛出此异常
 */
class CheckJwc extends BaseException
{
    public $msg = '教务网验证不通过';
    public $errorCode = 512;
}