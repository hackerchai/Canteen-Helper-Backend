<?php
namespace app\service;
use app\student\model\Cart as CartModel;
class Cart {
    public function getUserfulCart($buyId){
       $products=CartModel::with(["menu"])->where("buyId",$buyId)->where("status",0)->where("nums",">",0)->where("flag",1)->select();
       $data=[];
       if(!empty($products)){
           foreach($products as $product){
               array_push($data,$product->toArray());
           }
       }
       return $data;
    }
    public function sameMerchantByProdcuts($products){
        $data=[];   
        foreach($products as $product){
            $merchar_id=$product["menu"]['merchant_id'];        
            if(!array_key_exists($merchar_id,$data)){
                $data[$merchar_id]=[$product];
            }else{
                array_push($data[$merchar_id],$product);
            }
        }
        return $data;
    }
    public function sameMerchantInCart($buyId){
        $products=$this->getUserfulCart($buyId);
        return $this->sameMerchantByProdcuts($products);
    }
    public function createGoods($buyId){
        $carts=$this->sameMerchantInCart($buyId);
        $data=[];
        foreach($carts as $cart){
            if(is_array($cart)){
                $goods="";
                $nums="";
                foreach($cart as $a){
                    $goods.=$a['menuId'].',';
                    $nums.=$a['nums'].',';
                    $cartId=$a['id'].",";
                }
                $goods=substr($goods,0,strlen($goods)-1);
                $nums=substr($nums,0,strlen($nums)-1);
                $cartId=substr($cartId,0,strlen($cartId)-1);
                $temp['goods']=$goods;
                $temp['nums']=$nums;
                $temp["cartId"]=$cartId;
                array_push($data,$temp);
            }else{
                $temp['goods']=$cart['menuId'];
                $temp['nums']=$cart['nums'];
                $temp['cartId']=$cart['id'];
                array_push($data,$temp);
            }
        }
        return $data;
    }
}