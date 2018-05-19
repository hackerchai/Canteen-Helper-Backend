<?php
namespace app\service;
use app\student\model\Address;
use app\student\model\Cart as CartModel;
class Cart {
    public function createData($param){
        $products=[];
        $datas=$this->classifyByMerchantId($param);
        foreach ($datas as $k => $data){
            $product=[];
            $goods="";
            $nums="";
            for($i=0;$i<sizeof($data["goods"]);$i++){
                $goods.=$data["goods"][$i].",";
                $nums.=$data["nums"][$i].",";
            }
            $goods=substr($goods,0,strlen($goods)-1);
            $nums=substr($nums,0,strlen($nums)-1);
            $product["goods"]=$goods;
            $product["nums"]=$nums;
            $product["merchant_id"]=$k;
            array_push($products, $product);
        }
        return $products;
    }



    public function classifyByMerchantId($param){
        $data=[];
        $goods=$param["goods"];
        $nums=$param["nums"];
        $merchant=$param["merchant"];
        $is_post=$param["is_post"];
//        if($is_post){
//            $data["order_type"]=2;
//            $address=Address::where("uid",$id)->where("choose",1)->find()->toArray();
//            $address=$address["garden"].$address['building'].$address['room'];
//            $data['address']=$address;
//        }else{
//            $data["order_type"]=1;
//        }
        for($i=0;$i<sizeof($goods);$i++){
            if(!array_key_exists($merchant[$i], $data)){
                $data[$merchant[$i]]=[];
                $data[$merchant[$i]]["goods"]=[$goods[$i]];
                $data[$merchant[$i]]["nums"]=[$nums[$i]];
            }else{
                array_push( $data[$merchant[$i]]["goods"], $goods[$i]);
                array_push(  $data[$merchant[$i]]["nums"], $nums[$i]);
            }
        }
        return $data;
    }





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

        foreach($carts as $k => $cart){
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
                $temp["merchant_id"]=$k;
                array_push($data,$temp);
            }else{
                $temp['goods']=$cart['menuId'];
                $temp['nums']=$cart['nums'];
                $temp['cartId']=$cart['id'];
                $temp["merchant_id"]=$k;
                array_push($data,$temp);
            }
        }
        return $data;
    }
}