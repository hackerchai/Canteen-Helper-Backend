<?php
namespace app\student\controller;
use app\lib\validate\CodeValidate;
use app\service\Token as Toke;
use app\lib\success\Success;
use app\lib\validate\Token;
use app\lib\validate\PhoneValidate;
use app\student\model\Address;
use app\lib\validate\AddressValidate;
use app\lib\sms\Sms;
class User extends BaseController{
    public function login(){
        $cv=new CodeValidate();
        $param=$cv->goCheck();
        $code=$param['code'];
        $ut=new Toke($code,"student",'wx_canteen_helper');
        $success=new Success();
        $token=$ut->get();
        return $success->create([
            "token" => $token
        ]);   
    }
    public function identity(){
        $token=new Token();
       return $token->goCheck();
    }
    public function smsCode(){
        $pv=new PhoneValidate();
        $param=$pv->goCheck();
        $sms=new Sms();
        $res=$sms->sendSms($param["phone"]);
        return $this->succeed($res);

    }
    public function addresList(){
        $id=$this->getId();
        $address=new Address();
        return $this->succeed($address->getAddressByUid($id));
    }
    public function updateAddress(){
        $addr=new AddressValidate();
        $uid=$this->getId();
        $param =$addr->goCheck();
        $param["uid"]=$uid;
        if(empty($param['id'])){
            $addr=new Address($param);
            $addr->allowField(true)->save();
        }else{
            $addr=new Address();
            $addr->allowField(true)->save($param,["id"=>$param["id"]]);
        }
        return $this->succeed(['msg' => "1"]);
    }
}