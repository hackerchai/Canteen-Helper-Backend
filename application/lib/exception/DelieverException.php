<?php
namespace app\lib\exception;

/**
 * 404时抛出此异常
 */
class DelieverException extends BaseException
{
    public $msg = '你他娘的还不是配送员(-_-#)';
    public $errorCode = 405;
}