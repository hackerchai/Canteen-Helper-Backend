<?php
namespace app\lib\exception;

/**
 * 404时抛出此异常
 */
class OrderException extends BaseException
{
    public $msg = '你还没有登入请先登入';
    public $errorCode = 701;
}