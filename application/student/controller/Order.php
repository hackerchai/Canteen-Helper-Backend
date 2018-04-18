<?php
namespace app\student\controller;
use app\student\model\Student;
use app\service\Order as OrderService;
use app\lib\validate\OrderValidate;
use app\lib\validate\OrderIdValidate;
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
    
}