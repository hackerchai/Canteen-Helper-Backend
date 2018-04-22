<?php
namespace app\admin\model;
class Order extends BaseModel{
    protected $createTime = false;
    public function student(){
        return $this->hasOne("Student","id","buyer_id")->field("id,name,phone");
    }
    public function getAddressAttr($value){
            if(!empty($value)){
                return getAddressByStr($value);
            }else{
                return "该订单没有配送地址！！";
            }
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