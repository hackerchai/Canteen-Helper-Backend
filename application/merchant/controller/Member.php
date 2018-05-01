<?php
/**
 * Created by PhpStorm.
 * User: hackerchai
 * Date: 18-4-29
 * Time: 下午11:40
 **/
namespace app\merchant\controller;
use app\merchant\model\BaseModel;
use app\lib\validate\CodeValidate;
use app\merchant\controller\BaseController;
use app\lib\validate\LoginValidate;
use app\service\Token as Toke;
use app\lib\success\Success;
use app\lib\validate\Token;
use app\lib\validate\PhoneValidate;
use app\student\model\Address;
use app\lib\validate\AddressValidate;
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
    public function smsCode(){
        $pv=new PhoneValidate();
        $param=$pv->goCheck();
        $sms=new Sms();
        $res=$sms->sendSms($param["phone"]);
        return $this->succeed($res);
    }
    public function auth()
    {
        $rv=new RegisterValidate();
        $params=$rv->goCheck();
        $value=$this->getValue($params['token']);
        $openId=$value['openid'];
        $merchant=MerchantMember::where("open_id","=",$openId);
        $data["phone"]=$params['phone'];
        $merchant->update($data);
        return $this->succeed([
            "msg" =>$value['uid'],
        ]);
    }
}

