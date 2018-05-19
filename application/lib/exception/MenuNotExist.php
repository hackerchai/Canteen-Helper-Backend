<?php
namespace app\lib\exception;



class MenuNotExist extends BaseException
{

    public $msg = '菜单不存在';
    public $errorCode = 306;
}