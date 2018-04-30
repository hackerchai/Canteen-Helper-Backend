<?php
namespace app\lib\exception;

/**
 * 404时抛出此异常
 */
class WeChatException extends BaseException
{
    public $msg = 'code无效';
    public $errorCode = 300;
}