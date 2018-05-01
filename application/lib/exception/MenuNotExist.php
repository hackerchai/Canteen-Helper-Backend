<?php
namespace app\lib\exception;

/**
 * 404时抛出此异常
 */
class MenuNotExist extends BaseException
{
    
    public $msg = '菜单不存在';
    public $errorCode = 306;
}