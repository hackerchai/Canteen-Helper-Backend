<?php
/**
 * Created by PhpStorm.
 * User: hackerchai
 * Date: 18-4-29
 * Time: 下午11:40
 **/
namespace app\business\controller;
use app\business\model\BaseModel;
use app\lib\validate\CodeValidate;
use app\lib\validate\LoginValidate;
use app\service\Token as Toke;
use app\lib\success\Success;
use app\lib\validate\Token;
use app\lib\validate\PhoneValidate;
use app\student\model\Address;
use app\lib\validate\AddressValidate;
use app\lib\sms\Sms;
use app\business\model\Merchant;
use app\lib\validate\RegisterValidate;

class Member extends BaseModel
{

    public function login()
    {
        $lv = new CodeValidate();
        $param = $lv->goCheck();
        $code = $param['code'];
        $mt = new Toke($code,"merchant_member","wx_canteen_helper");
        $success = new Success();
        $token = $mt->get();
        return $success->create([
            "token" => $token,
        ]);
    }

    public function auth()
    {
        $rv=new RegisterValidate();
        $params=$rv->goCheck();
        $value=$this->getValue($params['token']);
        $openId=$value['openid'];
        $merchant=Merchant::where("open_id","=",$openId);
        $data["phone"]=$params['phone'];
        $merchant->update($data);
        return $this->succeed([
            "msg" =>$value['uid'],
        ]);
    }


}

