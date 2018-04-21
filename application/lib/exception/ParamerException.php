<?php
namespace app\lib\exception;

/**
 * 404时抛出此异常
 */
class ParamerException extends BaseException
{
    
    public $msg = '参数错误';
    public $errorCode = 404;
}