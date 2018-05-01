<?php
namespace app\merchant\controller;
use app\lib\validate\MerchantIdValidate;
use app\merchant\model\Order as OrderModel;
use app\service\Order as OrderService;
use app\lib\exception\SpoceException;
class Order extends BaseController
{

    public function today()
    {
        $ov=new MerchantIdValidate();
        $param=$ov->goCheck();
        $order=new OrderService($param["token"]);
        $data=$order->create();
        return $this->succeed($data);
    }


    public function handle()
    {
        $token = $this->getToken();
        $num = input("param.order_num");
        $is_accept = input("param.is_accept");
        $order = OrderModel::where("order_num", $num)->find();
        if ($order->status == "已提交") {
            if ($is_accept) {
                OrderModel::where("order_num", $num)->update(["status" => 4]);
            } else {
                OrderModel::where("order_num", $num)->update(["status" => 2]);
            }
        } else {
            throw new SpoceException();
        }
        return $this->succeed(['msg' => 1]);
    }

    public function sum()
    {
        $token=$this->getToken();
        $uid=$this->getId();
        $ordersc=new OrderModel();
        $orders=$ordersc->findTodayOrder($uid);
        $order=new OrderService($token);
        $res= $order->analysisOrder($orders);
        return $res;
    }

}