<?php
namespace app\merchant\model;
class Order extends BaseModel
{
    protected $createTime=false;
    public function getTodayOrder($merchant_id)
    {
        $time = date("Y-m-d",time());
        echo($time);
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