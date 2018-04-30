<?php
namespace app\lib\exception;

/**
 * 404时抛出此异常
 */
class AdminNotExist extends BaseException
{
    public $msg = '管理员不存在';
    public $errorCode = 700;
}