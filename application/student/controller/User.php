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
use app\student\model\Student;
use app\lib\validate\RegisterValidate;
use app\student\model\Cart;
use app\lib\jwc\Jwc;
use app\lib\exception\DefualtAddress;
class User extends BaseController{
    public function auth(){
        $re=new RegisterValidate();
        $params=$re->goCheck();
        $value=$this->getValue($params['token']);
        $openId=$value['openid'];
        $student=Student::where("open_id","=",$openId);
        $data["stu_id"]=$params['stu_id'];
        $data["stu_pass"]=$params['stu_pass'];
        $data["phone"]=$params['phone'];
        $student->update($data);
        return $this->succeed([
            "msg" =>$value['uid'],
        ]);
    }
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
    public function jwcIdentify(){
        $uid=$this->getId();
        $jwc=new Jwc(input("param."));
        $name=$jwc->getName();
        Student::where("id",$uid)->update(["name" => $name]);
        return $this->succeed(["msg" => true]);
    }

    public function identity(){
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
    public function addressList(){
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
            $ad=Address::where("uid",$uid)->find();
            if(empty($ad)){
                $param['choose']=1;
            }
            $addr=new Address();
            $addr->allowField(true)->save($param,["id"=>$param["id"]]);
        }
        return $this->succeed(['msg' => "1"]);
    }
    public function cartList(){
        $id=$this->getId();
        $data=[];
        $carts=Cart::with(['menu',"buy"])->where("buyId","=",$id)->where("nums",">",0)->where("status",0)->select();
        if(!empty($carts)){
            foreach($carts as $cart){
                array_push($data,$cart->toArray());
            }
        }
        return $this->succeed($data);
    }
    public function addAMenuToCart(){
        $id=$this->getId();
        $menuId=input("param.menuId");
        $mune=Cart::where("menuId",$menuId)->where("buyId",$id)->where("nums",">",0)->where("status",0)->find();
        if(!empty($mune)){
            $num=$mune->nums;
            $num++;
            $cartId=$mune->id;
            Cart::where("id",$cartId)->update(["nums" => $num]);
        }else{
            Cart::create([
                "buyId" =>$id,
                "menuId" =>$menuId,
                "nums" => 1,
            ]);
         }
         return $this->succeed([
             "msg" =>true
         ]);
    }
    public function changeFlag(){
        $uid=$this->getId();
        $cartId=input("param.cartId");
        if(!empty($cartId)){
        $cart=Cart::where("id",$cartId)->find();
        if($cart->flag){
            Cart::where("id",$cartId)->update(['flag' => 0]);
        }else{
            Cart::where("id",$cartId)->update(['flag' => 1]);
        }
      
        }else{
            $munes=Cart::where("buyId",$uid)->where("nums",">",0)->where("status",0)->select();
            if(!empty($munes)){
                foreach($munes as $mune){
                    $id=$mune->id;
                    Cart::where("id",$id)->update(['flag'=>1]);
                }
            }
            
        }
        return $this->succeed([
            "msg" =>true
        ]);
    }
    public function removeMenuFromCart(){
        $id=$this->getId();
        $menuId=input("param.menuId");
        $mune=Cart::where("menuId",$menuId)->where("buyId",$id)->where("nums",">",0)->where("status",0)->find();
        if(!empty($mune)){
            $num=$mune->nums;
            $num-=1;
            $cartId=$mune->id;
            Cart::where("id",$cartId)->update(["nums" => $num]);
        }
        return $this->succeed([
            "msg" =>true
        ]);
    }
    public function defaultAddress(){
        $uid=$this->getId();
        $address=Address::where("uid",$uid)->where("choose",1)->find();
        if(!empty($address)){
            return $this->succeed($address->toArray());
        }else{
            throw new DefualtAddress();
        
        }
    }
    public function updateDefualAddress(){
        $uid=$this->getId();

        $addressId=input("param.id");
        $address=Address::where("uid",$uid)->where("choose",1)->find();
        if(!empty($address)){
           Address::where("id",$address->id)->update(['choose'=>0]);
           Address::where("id",$addressId)->update(['choose'=>1]);
           return $this->succeed(['msg'=>true]);
        }else{
            throw new DefualtAddress();
        }
    }
    

}