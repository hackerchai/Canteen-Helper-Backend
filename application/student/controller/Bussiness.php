<?php
namespace app\student\controller;
use app\lib\validate\Token;
use app\student\model\Merchant;
use app\lib\validate\MenuValidate;
use app\student\model\Menu;
use app\service\Order;
class Bussiness extends BaseController{
    public function list(){
        $tn=new Token();
        $param=$tn->goCheck();
        $token=$param['token'];
        $merchart=Merchant::paginate();
        $v=$merchart->toArray();
        $data=$v['data'];
        return $this->succeed($data);
    }
    public function menu(){
        $me=new MenuValidate();
        $param=$me->goCheck();
        $menus=Menu::paginate();
        $v=$menus->toArray();
        $data=$v['data'];
        return $this->succeed($data);
    }
    public function orderList(){
        $token=$this->getToken();
        $id=$this->getId();
        $order=new Order($token);
       $res= $order->makeOrderByBuyId($id);
       return $this->succeed($res);
    }

}