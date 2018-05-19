<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2018.5.14
 * Time: 下午 10:31
 */

namespace app\deliever\controller;
use app\lib\success\Success;
use think\Controller;

class BaseController extends Controller
{
    protected function succeed($data){
        $su=new Success();
        return $su->create($data);
    }

}