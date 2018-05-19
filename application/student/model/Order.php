<?php
namespace app\student\model;
class Order extends BaseModel{
    protected $createTime = false;
    public  function findOrderById($id,$method=[]){
        $order=$this->with($method)->where("id","=",$id)->find();
        if(!empty($order)){
            return $order->toArray();
        }else{
            return false;
        }
    }
    public function findTodayOrderData($method=[],$merchant_id=null){
       $orders=$this->toSelect($method,$merchant_id);
        $data=[];
        if(!empty($orders)){
            foreach($orders as $order){
                $order=$order->getData();
                array_push($data,$order);
            }
        }
        return $data;
    }
    public function findTodayOrderRelativeData($orderType,$method=[]){
        $today=date("Y-m-d",time());
        $orders=$this->with($method)->where("create_time","like","%$today%")->where("order_type",$orderType)->select();
        $data=[];
        if(!empty($orders)){
            foreach($orders as $order){
                $order=$order->toArray();
                array_push($data,$order);
            }
        }
        return $data;
    }

    private  function toSelect($method=[],$merchant_id=null){
        $today=date("Y-m-d",time());
        if(!empty($merchant_id)){
            $orders=$this->with($method)->where("create_time","like","%$today%")->where("merchant_id",$merchant_id)->select();
        }else{
            $orders=$this->with($method)->where("create_time","like","%$today%")->select();
        }
        return $orders;
    }
    public function findOrderByOrderNum($orderNum,$method=[]){
        $order=$this->with($method)->where("order_num","=",$orderNum)->find();
        if(!empty($order)){
            return $order->toArray();
        }else{
            return false;
        }
    }
    public function findOrderByBuyId($buyId,$state,$method=[]){

        if(!isset($state)){
            $orders=$this->with($method)->where("buyer_id","=",$buyId)->select();
        }else {
            $orders = $this->with($method)->where("buyer_id", $buyId)->where("status", $state)->select();
        }
        $data=[];
        if(!empty($orders)){
            foreach($orders as $order){
            array_push($data,$order->toArray());
            }
        }
        return $data;
    }
    public function student(){
        return $this->hasOne("Student","id","buyer_id")->field("id,name");
    }
    public function merchant(){
        return $this->hasOne("Merchant","id","merchant_id")->field("id,merchant_name,merchant_loc,phone");
    }
    public function getStatusAttr($value){
        $status=[
            0 =>"等待支付结果",
            1=>"待取餐",
            2=>"已拒单",
            3=>"待支付",
            4 =>"已取餐",
        ];
        return $status[$value];
    }
    public function getOrderTypeAttr($value){
        $orderType=[
            0 => "预定自提堂食 ",
            1 => "预定自提打包",
            2 => "配送"
        ];
        return $orderType[$value];
    }
}