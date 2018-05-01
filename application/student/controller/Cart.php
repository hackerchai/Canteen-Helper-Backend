<?php
namespace app\student\controller;
use app\student\model\Cart as Carts;
class Cart extends BaseController{
    public function getUserfulCart(){
        $uid=$this->getId();
        $products=Carts::with(["buy","menu"])->where("buyId",$uid)->where("status",0)->where("nums",">",0)->where("flag",1)->select();
        $data=[];
        if(!empty($products)){
            foreach($products as $product){
                array_push($data,$product->toArray());
            }
        }
        return $this->succeed($data);
    }
}