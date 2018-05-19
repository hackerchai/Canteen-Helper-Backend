<?php
namespace app\lib\exception;

/**
 * 404时抛出此异常
 */
class TokenException extends BaseException
{
    public $msg = '权限不足';
    public $errorCode = 305;
}