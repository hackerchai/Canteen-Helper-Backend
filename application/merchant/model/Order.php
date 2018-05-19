<?php
namespace app\merchant\model;
class Order extends BaseModel
{
    protected $createTime=false;
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
    public function getTodayOrder($merchant_id)
    {
        $time = date("Y-m-d",time());
        $orders = $this->with(["buyer"])->where("create_time","LIKE","%$time%")->where("merchant_id","=","$merchant_id")->select();
        $data=[];
        if(!empty($orders))
        {
            foreach($orders as $order)
            {
                array_push($data,$order->toArray());
            }
        }

        return $data;

    }

    public function buyer()
    {
        return $this->hasOne("Student","id","buyer_id");
    }


//    public function getStatusAttr($value){
//        $status=[
//            0 =>"等待支付结果",
//            1=>"待取餐",
//            2=>"已拒单",
//            3=>"待支付",
//            4 =>"已取餐",
//        ];
//        return $status[$value];
//    }
//    public function getOrderTypeAttr($value){
//        $orderType=[
//            0 => "预定自提堂食 ",
//            1 => "预定自提打包",
//            2 => "配送"
//        ];
//        return $orderType[$value];
//    }
}