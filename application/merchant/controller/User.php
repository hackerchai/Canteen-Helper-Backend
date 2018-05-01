<?php
/**
 * Created by PhpStorm.
 * User: hackerchai
 * Date: 18-4-29
 * Time: 下午11:40
 **/
namespace app\merchant\controller;
use app\lib\validate\RegisterValidate;
use app\merchant\model\MerchantMember;
class User extends BaseController{
    public function auth(){
        $re=new RegisterValidate();
        $uid=$this->getId();
        $param=$re->goCheck();
        $phone=$param['phone'];
        MerchantMember::where("id",$uid)->update(['phone' => $phone]);
        return $this->succeed(["msg" => 1]);
    }
}