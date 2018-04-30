<?php
namespace app\lib\exception;

/**
 * 404时抛出此异常
 */
class DefualtAddress extends BaseException
{
    public $msg = '默认的地址不存在';
    public $errorCode = 702;
}