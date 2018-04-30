<?php
namespace app\lib\exception;

/**
 * 404时抛出此异常
 */
class TokenException extends BaseException
{
    public $msg = 'token无效或者已经过期';
    public $errorCode = 405;
}