<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2018.5.14
 * Time: 下午 10:34
 */

namespace app\deliever\controller;


class Order extends BaseController
{
    public function toget(){
        $token="dd";
        $order=new \app\service\Order($token);
        $orders=$order->getDeliverOrder(2);
        return $this->succeed($orders);
    }

}