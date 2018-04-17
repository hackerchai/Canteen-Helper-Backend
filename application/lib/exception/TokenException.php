<?php
namespace app\lib\exception;

/**
 * 404时抛出此异常
 */
class TokenException extends BaseException
{
    public $code = 404;
    public $msg = '生成令牌错误';
    public $errorCode = 10001;
}