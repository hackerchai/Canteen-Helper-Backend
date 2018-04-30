<?php
namespace app\student\controller;
use app\student\model\Student;
use app\service\Order as OrderService;
use app\lib\validate\OrderValidate;
use app\lib\validate\OrderIdValidate;
use app\student\model\Address;
use app\student\model\Cart;
use app\service\Cart as CartService;
class Order extends BaseController{
    public function create(){
        $oe=new OrderValidate();
        $param=$oe->goCheck();
        $order=new OrderService($param["token"]);
        $data= $order->create($param);
        return $this->succeed($data);
    }
    public function submit(){
        $oe=new OrderIdValidate();
        $param=$oe->goCheck();
        $order=new OrderService($param["token"]);
        $data= $order->updatdOrderStatus($param['order_id']);
        return $this->succeed($data);
    }
    public function makeOrder(){
        $id=$this->getId();
        $token=$this->getToken();
        $flag=input('param.flag');
        $order=new OrderService($token);
        $address="";
        $param=[];
        if($flag){
            $param['order_type']=2;
            $address=Address::where("uid",$id)->where("choose",1)->find()->toArray();
            $address=$address["garden"].$address['building'].$address['room'];
            $param['address']=$address;
        }else{
            $param['order_type']=1; 
        }
        $cart=new CartService();
        $products=$cart->createGoods($id); 
        foreach($products as $product){
            $param['goods']=$product['goods'];
            $param['nums']=$product['nums'];
            $order->create($param);
            Cart::where("id",$product['cartId'])->update(['status'=>1]);
        }
        return $this->succeed(["msg" =>true]);
        
    }
    
}