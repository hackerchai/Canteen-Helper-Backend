<?php
namespace app\merchant\controller;
use app\merchant\model\Merchant;
use app\student\model\Menu;
class Dish extends BaseController{
    public function lists(){
        $uid=$this->getId();
        $menus=Menu::where("merchant_id",$uid)->select();
        $data=[];
        if(!empty($menus)){
            foreach($menus as $menu){
                array_push($data,$menu->toArray());
            }
        }
        return $this->succeed($data);
    }
    public function switchSoldOut(){
        $this->getToken();
        $id=input("param.id");
        $menu=Menu::where("id",$id)->find();
        if(!empty($menu)){
            if($menu->is_sold_out==1){
                Menu::where("id",$id)->update(["is_sold_out"=>0]);
                return $this->succeed(["stutas" => 0]);
            } else {
                Menu::where("id",$id)->update(["is_sold_out"=>1]);
                return $this->succeed(['stutas' =>1]);
            }
        }else{

        }
    }
    public function delete(){
        $this->getToken();
        $id=input("param.id");
        $menu=Menu::where("id",$id)->find();
        if(!empty($menu)){
            Menu::get($id)->delete();
        }
        return $this->succeed(['msg' =>1]);
    }
    public function getAll(){
        $this->getToken();
        $merchants=Merchant::field("id,merchant_name")->select();
        $data=[];
        foreach ($merchants as $merchant){
            array_push($data, $merchant->toArray());
        }
        $merchants=[];
        $merchants[0]="请选择店铺";
        foreach($data as $v){

            $merchants[$v["id"]]=$v["merchant_name"];
        }
        return $this->succeed($merchants);
    }
    public function add(){
        $this->getToken();
        $param=input("param.");
        $id=$param["id"];
        $menu=Menu::where("id",$id)->find();
        $data["meal_name"]=$param['name'];
        $data['meal_price']=$param["price"];
        $data["merchant_id"]=$this->getId();
        $flag=$param["flag"];
        if($flag) {
            $file = request()->file('image');
            $info = $file->move(ROOT_PATH . 'static' . DS . 'uploads');
            if ($info) {
                $image = $info->getSaveName();
                $root = request()->domain();
                $image = $root . DS . "static" . DS . "uploads" . DS . $image;
                $data['picture'] = $image;
            } else {
                throw new ImageException(["msg" => $file->getError()]);
            }
        }
        if(!empty($menu)){
           Menu::where("id",$id)->update($data);
        }else{
            Menu::create($data);
        }
        return $this->succeed(['msg' =>1]);
     
        
    }
}