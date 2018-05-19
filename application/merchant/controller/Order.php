<?php
namespace app\merchant\controller;

use app\lib\exception\OrderException;
use app\lib\exception\OrderNotExist;
use app\merchant\model\MerchantMember;
use app\merchant\model\Order as OrderModel;
use app\service\Order as OrderService;
use app\service\OrderMerchant as OrderMerchantService;
use app\lib\exception\SpoceException;
use app\service\Token;

class Order extends BaseController
{

    public function today()
    {
//        $ov=new MerchantIdValidate();
//        $param=$ov->goCheck();

        $token=$this->getToken();
        $merchant_id=$this->getId();
        $sorder=new OrderMerchantService($token);
        $data=$sorder->get($merchant_id);
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
        $uid=Token::getVarByToken($token, "uid");
        $merchant_id=MerchantMember::get($uid);
        $merchant_id=$merchant_id->toArray()["merchant_id"];
        $ordersc=new OrderModel();
        $orders=$ordersc->findTodayOrder($merchant_id);
        $order=new OrderService($token);
        $res= $order->analysisOrder($orders);
        return $this->succeed($res);
    }
    public function takeOrder(){
        $token=$this->getToken();
        $num=input("param.num");
        $order=new \app\merchant\model\Order();
        $order=$order->where("order_num",$num)->find();
        if(empty($order)){
            throw new OrderNotExist();
        }
        $order=$order->toArray();
        $oid=$order["id"];
        $status=$order["status"];
        if($status==4){
            throw  new OrderException();
        }else{
            \app\merchant\model\Order::update(["id"=>$oid,
                "status" =>4
                ]);
            $order=new OrderService($token);
            $order=$order->makeResult($oid);
            return $this->succeed($order);
        }

    }
}