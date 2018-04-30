<?php
namespace app\service;
use think\Cache;
use app\merchant\model\Order as OrderModel;
use app\merchant\model\Menu;
use app\lib\exception\OrderException;

class OrderMerchant{
    protected $goods;
    protected $nums;
    protected $token;
    //构造函数初始化token
    public function __construct($token){

        $this->token=$token;
    }

    public function get()
    {
        $merchant_id=$this->getIdByToken($this->token);
        $order=(new OrderModel())->getTodayOrder($merchant_id);
        $data=$this->makeData($order);
        return $data;
    }




    private function calculateSumMoney($sources)
    {
        $sum_money = 0.00;
        foreach ($sources as $source)
        {
            $sum_money+=$source["money"];

        }
        return $sum_money;

    }

    private function makeData($sources)
    {
        $datas=[];
        foreach ($sources as $source)
        {
            $data=[];
            $goods=$this->strToArray($source['goods'],",");
            $nums=$this->strToArray($source['nums'],",");
            $menus=(new Menu())->findMenus($goods,$nums,"id,meal_name,meal_price,picture");
            $data["order"]=$source['student']['name'];
            $data['order_num']=$source["order_num"];
            $data["price"]=$source["money"];
            $data['status']=$source['status'];
            $data["goods"]=$menus;
            $data["price"]=$source["money"];
            $data['create_time']=$source["create_time"];
            $data['order_type']=$source['order_type'];
            array_push($datas,$data);
        }
        $today_sum=$this->calculateSumMoney();
        $datas["today_sum"]=$today_sum;

        return $datas;
    }





    //将字符串变成数组
    private function strToArray($str,$signal){
        if(strpos($str,$signal)){
            $arr=explode($signal,$str);
        }else{
            $arr=$str;
        }
        return $arr;
    }
    //得到id
    private function getIdByToken($token){
        $v=Cache::get($token);
        $v=json_decode($v,true);
        return $v["uid"];
    }
}