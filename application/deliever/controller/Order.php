<?php

namespace app\deliever\controller;
use app\deliever\model\BaseModel;
use app\deliever\controller\BaseController;
use app\lib\validate\DelieverValidate;
use app\service\Token as Toke;
use app\lib\success\Success;
use app\lib\validate\Token;
use app\deliever\model\DelieverOrder;
use think\Db;

class Order extends BaseController
{

    public function toget(){
        // $oe=new DelieverValidate();
        // $param=$oe->goCheck();
        $param["token"] = "1546465456";
        $order=new DelieverOrder($param["token"]);
        $data= $order->list($param["token"]);
        return $this->succeed($data);

    }
}

