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

//        $token=$this->getToken();
//        $flag=input('param.flag');
//        $order=new OrderService($token);



        $id=$this->getId();
        $param=input("param.");
        $cart=new CartService();
        $products=$cart->createData($param);
        $token=$this->getToken();
        $order=new OrderService($token);
        $address="";
        $flag=$param["is_post"];
        $param=[];
        if($flag==2){
            $param['order_type']=2;
            $address=Address::where("uid",$id)->where("choose",1)->find()->getData();
            $address=$address["garden"].$address['building'].$address['room'];
            $param['address']=$address;
        }else{
            $param['order_type']=$flag;
        }

        foreach($products as $product){
            $param['goods']=$product['goods'];
            $param['nums']=$product['nums'];
            $param['merchant_id']=$product['merchant_id'];
            $order->create($param);
        }
        return $this->succeed(["msg" => true]);
        
    }
    
}