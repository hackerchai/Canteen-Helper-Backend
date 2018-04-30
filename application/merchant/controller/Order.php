<?php
namespace app\merchant\controller;
use app\student\model\Order as OrderModel;
class Order extends BaseController
{

    public function today(){
        $ov=new OrderValidate();
        $param=$ov->goCheck();
        $order=new OrderService($param["token"]);
        $data=$order->create();
        return $this->succeed($data);
    }


    public function sum()
    {

    }
}