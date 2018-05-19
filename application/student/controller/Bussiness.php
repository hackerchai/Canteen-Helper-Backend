<?php
namespace app\student\controller;
use app\lib\validate\Token;
use app\student\model\Merchant;
use app\lib\validate\MenuValidate;
use app\student\model\Menu;
use app\service\Order;
class Bussiness extends BaseController{

    public function list(){
        $tn=new Token();
        $param=$tn->goCheck();
        $page=array_key_exists("page",$param)?$param['page']:1;
        $size=array_key_exists("size",$param)?$param['size']:5;
        $merchants=Merchant::where("is_verified",0)->page($page,$size)->select();
        $data=[];
        foreach($merchants as $merchant){
            array_push($data,$merchant->toArray());
        }
        return $this->succeed($data);
    }
    public function getAMerchant(){
        $token=$this->getToken();
        $id=input("param.merchant_id");
        $merchant=Merchant::get($id)->toArray();
        return $this->succeed($merchant);
    }
    public function menu(){
        $me=new MenuValidate();
        $param=$me->goCheck();
        $page=array_key_exists("page",$param)?$param['page']:1;
        $size=array_key_exists("size",$param)?$param['size']:5;
        $menus=Menu::where("merchant_id","=",$param['merchant_id'])->page($page,$size)->select();
        $data=[];
        foreach($menus as $menu)
        {
            array_push($data,$menu->toArray());
        }
       
        return $this->succeed($data);
    }
    public function orderList(){
        $token=$this->getToken();
        $id=$this->getId();
        $state=input("param.status");
        $order=new Order($token);
       $res= $order->makeOrderByBuyId($id,$state);
       return $this->succeed($res);
    }
    public function orderListByState(){

    }

}