<?php
namespace app\lib\exception;

/**
 * 404时抛出此异常
 */
class OrderException extends BaseException
{
    public $msg = '已经提取了这个订单';
    public $errorCode = 801;
}