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
    public function findTodayOrder($merchant_id){
        $today=date("Y-m-d",time());
        $orders=$this->where("create_time","like","%$today%")->where("merchant_id",$merchant_id)->select();
        $data=[];
        if(!empty($orders)){
            foreach($orders as $order){
                $order=$order->getData();
                array_push($data,$order);
            }
        }
        return $data;
    }
    public function findOrderByOrderNum($orderNum,$method=[]){
        $order=$this->with($method)->where("order_num","=",$orderNum)->find();
        if(!empty($order)){
            return $order->toArray();
        }else{
            return false;
        }
    }
    public function findOrderByBuyId($buyId,$method=[]){
        $orders=$this->with($method)->where("buyer_id","=",$buyId)->select();
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
    public function getStatusAttr($value){
        $status=[
            0 =>"已创建",
            1=>"已提交",
            2=>"已拒绝",
            3 =>"已付款",
            4 => "已接单",
            5 =>"已完成",
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