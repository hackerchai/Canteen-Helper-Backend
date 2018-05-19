<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2018.5.19
 * Time: 上午 12:29
 */

namespace app\lib\exception;


class OrderNotExist extends BaseException
{
    public $msg="二维码无效";
    public $errorCode=800;

}