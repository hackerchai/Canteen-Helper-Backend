<?php
namespace app\merchant\controller;
use app\student\model\Order as OrderModel;
class Order extends BaseController{
    public function handle(){
        $token=$this->getToken();
        $num=input("param.nums");
        //$order=OrderModel::where("order_num",$num)->update(['status' => ])

        
    }
}