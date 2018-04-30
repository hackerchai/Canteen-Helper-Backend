<?php
namespace app\admin\controller;
use app\admin\model\Order as Orders;
class Order extends BaseController{
    public function index(){
        $orders=Orders::paginate(1);
        $show=$orders->render();
        $this->assign("show",$show);
        $this->assign("orders",$orders);
        return $this->fetch();
    }
    public function sum(){
     
    }
}