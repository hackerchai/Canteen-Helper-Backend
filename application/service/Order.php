<?php
namespace app\service;
use think\Cache;
use app\student\model\Order as OrderModel;
use app\student\model\Menu;
use app\lib\exception\OrderException;
class Order{
    protected $goods;
    protected $nums;
    protected $orderType;
    protected $address;
    protected $token;
    public function __construct($token){
        $this->token=$token;
    }
    public function setAttr($param){
        $this->goods=$param['goods'];
        $this->nums=$param['nums'];
        $this->orderType=$param['order_type'];
       
        if(!empty($param['address'])){
            $this->address=$param["address"];
        }
    }
    public function create($param){
        $data=[];
        $this->setAttr($param);
        $data['buyer_id']=$this->getIdByToken($this->token);
        $data['order_num']=$this->createOrderNum();
        $data['status']=0;
        $data['order_type']=$this->orderType;
        if(!empty($this->address)){
           $data['address']=$this->address;
        }
        $data['goods']=$this->goods;
        $data['nums']=$this->nums;
        $data['create_time']= date("Y-m-d h:i:s",time());
        $order=OrderModel::create($data);
        $orderId=$order->id;
        $this->updateOrderMoney($orderId);
        $data= $this->makeResult($orderId);
        return $data;
    }
    public function updatdOrderStatus($orderId){
        $order=new OrderModel();
        $order=$order->where("order_num","=",$orderId)->find();
        if(!empty($order)){
            $stutas=$order->getData('status');
            $stutas++;
            $order->save(["status" =>$stutas ]);
            return ["msg" => 1];
        }else{
            throw new OrderException([
                "msg" => "订单号不存在",
            ]);
        }
    }
    public function updateOrderMoney($id){
        $order=OrderModel::get($id);
        $sumMoney=$this->calculatorOrderMoney();
        $order->save(["money"=>$sumMoney]);
    }
    public function calculatorOrderMoney(){
        $goods=$this->strToArray($this->goods,",");
        $nums=$this->strToArray($this->nums,",");
        $menuModel=new Menu();
        $menus=$menuModel->findMenus($goods);
        $sumMoney=0.00;
        if(is_array($goods)){
            for($i=0;$i<sizeof($menus);$i++){
                $sumMoney+=$menus[$i]["meal_price"]*$nums[$i];
            }
        }else{
            
            $sumMoney=$menus["meal_price"]*$nums;
        }
        return $sumMoney;
    }
    public function makeOrderByBuyId($buyId){
        $orders=(new OrderModel())->findOrderByBuyId($buyId,["student"]);
        $data=[];
        foreach($orders as $order){
            array_push($data,$this->makeData($order));
        }
        $res["orders"]=$data;
        return $res;
    }
    public  function makeResult($id){
        $order=(new OrderModel())->findOrderById($id,["student"]);
        $data=$this->makeData($order);
        return $data;
    }
    private function makeData($order){
        $data=[];
        $goods=$this->strToArray($order['goods'],",");
        $menus=(new Menu())->findMenus($goods,"id,meal_name,meal_price");
        $nums=$this->strToArray($order['nums'],".");
        $data['id']=$order['id'];
        $address=$order['address'];
        $data["order"]=$order['student']['name'];
        $data['order_num']=$order["order_num"];
        $data["goods"]=$menus;
        $data['nums']=$order['nums'];
        if(!empty($address)){
            $address=$this->getAddressByStr($address);
            $data["address"]=$address;
        }
        $data["price"]=$order["money"];
        $data['create_time']=$order["create_time"];
        $data['order_type']=$order['order_type'];
        return $data;
    }
    private function strToArray($str,$signal){
        if(strpos($str,$signal)){
            $arr=explode($signal,$str);
        }else{
            $arr=$str;
        }
        return $arr;
    }
   
    private function getIdByToken($token){
        $v=Cache::get($token);
        $v=json_decode($v,true);
        return $v["uid"];
    }
    private function createOrderNum(){
        $time=date("Y-m-d h:i:s",time());
        $time=str_replace(":","",$time);
        $time=str_replace("-","",$time);
        $time=str_replace(" ","",$time);
        $num=getRandNum(4);
        return $time.$num;
    }
    private function getAddressByStr($str){
        $str=$str."";
        $l=strlen("$str");
        if($l<5)
        {
            return;
        }
        $address=config("address");
        $garden=$address['garden'];
        $building=$address['building'];
        $address=$garden[$str[0]].$building[$str[1]].substr($str,2);
        return $address;
    }
}