<?php
namespace app\merchant\controller;
use app\student\model\Order as OrderModel;
use app\service\Order as OrderService;
use app\lib\exception\SpoceException;
class Order extends BaseController{
    public function handle(){
        $token=$this->getToken();
        $num=input("param.order_num");
        $num=input("param.is_accept");
        $order=OrderModel::where("order_num",$num)->find();
        if($order->status==1){
            $orderSe=new OrderService($token);
            $orderSe->updatdOrderStatus($num);
        }else{
            throw new SpoceException();
        }
        return $this->succeed(['msg' => 1]);
    }
   
}