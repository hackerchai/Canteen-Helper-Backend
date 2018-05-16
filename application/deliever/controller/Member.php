<?php
/**
 * Created by PhpStorm.
 * User: hackerchai
 * Date: 18-4-29
 * Time: 下午11:40
 **/
namespace app\deliever\controller;
use app\deliever\model\BaseModel;
use app\lib\validate\CodeValidate;
use app\deliever\controller\BaseController;
use app\lib\validate\LoginValidate;
use app\service\Token as Toke;
use app\lib\success\Success;
use app\lib\validate\Token;
use app\lib\validate\PhoneValidate;
use app\student\model\Address;
use app\lib\validate\AddressValidate;
use app\lib\sms\Sms;
use app\deliever\model\MerchantMember;
use app\lib\validate\RegisterValidate;
use app\lib\exception\DelieverException;
use think\Db;
class Member extends BaseController
{

    public function login()
    {
        $lv = new CodeValidate();
        $param = $lv->goCheck();
        $code = $param['code'];
        $mt = new Toke($code,"ch_student","wx_canteen_admin");
        $success = new Success();
        $token = $mt->get();
        //验证是不是配送员
        $delieverData = Db::name("student")->where("token",$token)->find();
        if(!$delieverData["is_deliver"]){
            $e=new DelieverException([
            ]);
            throw $e;
        }
        return $success->create([
            "token" => $token,
        ]);
    }
}

