<?php
namespace app\merchant\controller;
use app\student\model\Order as OrderModel;
class Order extends BaseController
{

    public function handle(){
        $token=$this->getToken();
        $num=input("param.order_num");
        $order=OrderModel::where("order_num",$num)->find();
        if($order->status==1){
            $orderSe=new OrderService($token);
            $orderSe->updatdOrderStatus($num);
        }else{
            throw new SpoceException();
        }
        return $this->succeed(['msg' => 1]);
    }


    public function sum()
    {

    }
}