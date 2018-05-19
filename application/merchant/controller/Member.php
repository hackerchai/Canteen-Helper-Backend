<?php
/**
 * Created by PhpStorm.
 * User: hackerchai
 * Date: 18-4-29
 * Time: 下午11:40
 **/
namespace app\merchant\controller;
use app\lib\validate\MerchantRegister;
use app\merchant\model\BaseModel;
use app\lib\validate\CodeValidate;
use app\merchant\controller\BaseController;
use app\lib\validate\LoginValidate;
use app\merchant\model\Merchant;
use app\service\Token as Toke;
use app\lib\success\Success;
use app\lib\validate\Token;
use app\lib\validate\PhoneValidate;
use app\lib\sms\Sms;
use app\merchant\model\MerchantMember;
use app\lib\validate\RegisterValidate;

class Member extends BaseController
{

    public function login()
    {
        $lv = new CodeValidate();
        $param = $lv->goCheck();
        $code = $param['code'];
        $mt = new Toke($code,"merchant_member","wx_canteen_admin");
        $success = new Success();
        $token = $mt->get();
        return $success->create([
            "token" => $token,
        ]);
    }
    public function check(){
        $this->getToken();
        return $this->succeed(["msg" => true]);
    }

    public function smsCode(){
        $pv=new PhoneValidate();
        $param=$pv->goCheck();
        $sms=new Sms();
        $res=$sms->sendSms($param["phone"]);
        return $this->succeed($res);
    }
    public function auth()
    {
        $rv=new MerchantRegister();
        $params=$rv->goCheck();
        $uid=Toke::getVarByToken($params["token"], "uid");
        $merchant=MerchantMember::where("id",$uid);
        $data["phone"]=$params['phone'];
        $data["name"]=$params["name"];
        $merchant->update($data);
        return $this->succeed([
            "msg" =>$uid,
        ]);
    }
    public function identify(){
        $token=$this->getToken();
        $uid=Toke::getVarByToken($token, "uid");
        $member=MerchantMember::get($uid);
        return $this->succeed(["msg" => $member->is_verified]);
    }

}

