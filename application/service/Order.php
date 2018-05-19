<?php
namespace app\service;
use app\lib\exception\MenuNotExist;
use think\Cache;
use app\student\model\Order as OrderModel;
use app\merchant\model\Order as OrderMerchant;
use app\student\model\Menu;
use app\lib\exception\OrderException;
use app\lib\qrcode\Qcode;
class Order{
    protected $goods;
    protected $nums;
    protected $orderType;
    protected $address;
    protected $token;
    //构造函数初始化token
    public function __construct($token){
        $this->token=$token;
    }
    public function getDeliverOrder($order_type){
        $orderModel=new OrderModel();
        $orders=$orderModel->findTodayOrderRelativeData(2,["student","merchant"]);
        $res=[];
        $res["order"]=[];
        foreach ($orders as $order){
            array_push($res['order'], $this->makeData($order));
        }
        $orders=$orderModel->findTodayOrderData();
        $orders=$this->analysisOrderByType($orders);
        $orders=$orders[$order_type];
        $data=[];
        $data[$order_type]=$this->analysisOrderByGoods($orders);
        $data=$this->changeIdOnRes($data);
        $data=$this->getMenuByIds($data);
        $res["count"]=$data;
        return $res;

    }
    //初始化类里面的属性
    public function setAttr($param){
        $this->goods=$param['goods'];
        $this->nums=$param['nums'];
        $this->orderType=$param['order_type'];
       
        if(!empty($param['address'])){
            $this->address=$param["address"];
        }
    }
    /**
     * 创建订单
     * @param array $param 客户端的api对应的数组对
     * @return array $data 根据api返回的数组
     */
    public function create($param){
        $data=[];
        $this->setAttr($param);
        $data['buyer_id']=$this->getIdByToken($this->token);
        $data['order_num']=$this->createOrderNum();
        $data['status']=0;
        $data["merchant_id"]=$param["merchant_id"];
        $data['order_type']=$this->orderType;
        if(!empty($this->address)){
           $data['address']=$this->address;
        }
        $data['goods']=$this->goods;
        $data['nums']=$this->nums;
        $data['create_time']= date("Y-m-d h:i:s",time());
        $data['qrcode']=$this->createQRcodeByText($data['order_num']);
        $order=OrderModel::create($data);
        $orderId=$order->id;
        $this->updateOrderMoney($orderId);
        $data= $this->makeResult($orderId);
        return $data;
    }
    /**
     * 更新订单的状态，每调用一次这个方法，订单将进入下个状态
     * @param string $orderId 订单的号
     * @throw OrderException
     * @return array 成功则msg=1,返回给客户端
     */
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
    public function getOrderSum(){
        
    }
    /**
     * 创建订单二维码
     * @param string $text 字符串
     * @return string $qrcode 二维码的绝对地址
     */
    public function createQRcodeByText($text){
       $text=config("qr.controller").$text;
        Qcode::png($text);
        $name=Qcode::getRelativeName();
        $root=request()->domain();
        $url=$root.DS."static".DS."qrcode".DS.$name;
        return  $url;
    }
    /**
     * 更新订单的钱
     * @param int $id 订单id
     */
    public function updateOrderMoney($id){
        $order=OrderModel::get($id);
        $sumMoney=$this->calculatorOrderMoney();
        $order->save(["money"=>$sumMoney]);
    }
    /**
     * 计算一个订单的金额
     */
    private function calculatorOrderMoney(){
        $goods=$this->strToArray($this->goods,",");
        $nums=$this->strToArray($this->nums,",");
        $menuModel=new Menu();
        $menus=$menuModel->findMenus($goods,$nums);

        $sumMoney=0.00;
        for($i=0;$i<sizeof($menus);$i++){
           $sumMoney+=$menus[$i]["meal_price"]*$nums[$i];
         }
        return $sumMoney;
    }
    public function analysisOrder($orders){
        $res=$this->analysisOrderByType($orders);
        $data=[];
        foreach($res as $key =>$v){
            $data[$key]=$this->analysisOrderByGoods($res[$key]);
        }
        $data=$this->changeIdOnRes($data);
        $data=$this->getMenuByIds($data);
        return $data;
    }


    /**
     * 分析orders通过goods_id
     * 将一个今日的全部订单 通过goods_id 分选出来, 同一个good其数量会改变,
     * */
    private function analysisOrderByGoods($datas){
        $data=[];
        for($i=0;$i<sizeof($datas);$i++){    
            $d=$datas[$i];       
            $goods= $d['goods'];
            $nums=$d["nums"];
            if(strpos($goods,",")){
                $goods=explode(",",$goods);
                $nums=explode(",",$nums);
                foreach($goods as $key =>$good){
                    if(array_key_exists($good,$data)){
                        array_push($data[$good],$nums[$key]);
                    }else{
                      $data[$good]=[];
                      array_push($data[$good],$nums[$key]);
                    }
                }
            }else{
                if(array_key_exists($goods,$data)){
                    array_push($data[$goods],$nums);
                }else{
                    $data[$goods]=[];
                  array_push($data[$goods],$nums);
                }
            }
        }
        foreach($data as $key => $d){
            $sum=0;
          foreach($d as $v){
              $sum+=$v;
          }
          $data[$key]=$sum;
        }
        return $data;
    }
    /**
     * 将订单数据按照order_type进行分类处理
     * @param array $order 原始的订单数据
     * @return array $data [order_type : {goods :nums}]
     * */
    private function analysisOrderByType($orders){
        $data=[];
        foreach($orders as $order){
            $order_type=$order['order_type'];
            if(array_key_exists($order_type,$data)){
                array_push($data[$order_type],$order);
            }else{
                $data[$order_type]=[];
                array_push($data[$order_type],$order);
            }
        }
        return $data;

    }
    /**
     * 交换id与键值key,让菜单id作为key,键值作为份数
     * @param array $data 经过analysisOrderByType函数的订单数据
     * @return array $data [ "goods_Id":{ type_id : nums }]
     * */
    private function changeIdOnRes($datas){
        $data=[];
        foreach($datas as $ty => $pi){
            foreach($pi as $k =>$v){
                if(array_key_exists($k,$data)){
                    if(!array_key_exists($ty,$data[$k])){
                        $data[$k][$ty]=0;
                    }
                    $data[$k][$ty]+=$v;
                }else{
                    $data[$k]=[];
                    $data[$k][$ty]=$v;

                }
            }
        }
        return $data;
    }
    /**
     * 得到今日菜单通过菜单ids
     * @param array $data 菜单id数组
     * @throw MenuNotExist 菜单不存在
     * @return array $data 菜单数组
     * */
    public function getMenuByIds($data){
        foreach($data as $k => $v){
            $mune=Menu::where("id",$k)->find();
            if(!empty($mune)){
                $data[$k]["menu"]=$mune->toArray();
            }else{
                throw new MenuNotExist(["msg" => "菜单已经被删除,无法查看今日订单"]);
            }
        }
        return $data;

    }
    /**
     * 得到订单通过购买者的id
     * @param int $buyId 购买者的id
     * @return 订单数据
     */
    public function makeOrderByBuyId($buyId,$state){
        $orders=(new OrderModel())->findOrderByBuyId($buyId,$state,["student","merchant"]);
        $data=[];
        foreach($orders as $order){
            array_push($data,$this->makeData($order));        
        }
        $res["orders"]=$data;
        return $res;
    }
    /**
     * 获取订单结果通过订单id
     */
    public  function makeResult($id){
        $order=(new OrderModel())->findOrderById($id,["student","merchant"]);
        $data=$this->makeData($order);
        return $data;
    }
    /**
     * 制造与api相匹配的数据
     * @param array $order 一条order表里面的查询数据
     * @return array $data api对应的data
     */
    private function makeData($order){
        $data=[];
        $goods=$this->strToArray($order['goods'],",");
        $nums=$this->strToArray($order['nums'],",");
        $menus=(new Menu())->findMenus($goods,$nums,"id,meal_name,meal_price,picture");
        $data['id']=$order['id'];
        $address=$order['address'];
        $data['qrcode']=$order['qrcode'];
        $data["merchant_name"]=$order['merchant']['merchant_name'];
        $data["merchant_loc"]=$order["merchant"]["merchant_loc"];
        $data['merchant_phone']=$order['merchant']['phone'];
        $data["order"]=$order['student']['name'];
        $data['order_num']=$order["order_num"];
        $data['status']=$order['status'];
        $data["goods"]=$menus;
      //  $data['nums']=$order['nums'];
        if(!empty($address)){
            $address=$this->getAddressByStr($address);
            $data["address"]=$address;
        }
        $data["price"]=$order["money"];
        $data['create_time']=$order["create_time"];
        $data['order_type']=$order['order_type'];
        return $data;
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
   //得到用户id
    private function getIdByToken($token){
        $v=Cache::get($token);
        $v=json_decode($v,true);
        return $v["uid"];
    }
    //创建订单号
    private function createOrderNum(){
        $time=date("Y-m-d h:i:s",time());
        $time=str_replace(":","",$time);
        $time=str_replace("-","",$time);
        $time=str_replace(" ","",$time);
        $num=getRandNum(4);
        return $time.$num;
    }
    //得到地址
    public function getAddressByStr($str){
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