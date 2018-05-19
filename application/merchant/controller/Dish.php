<?php
namespace app\merchant\controller;
use app\student\model\Menu;
use app\lib\exception\MenuNotExist;
class Dish extends BaseController{
    public function list(){
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
        $flag=input("param.flag");
        $menu=Menu::where("id",$id)->find();
        if(!empty($menu)){
            if($flag){
                if($menu->is_sold_out==0){
                    Menu::where("id",$id)->update(["is_sold_out"=>1]);
                    return $this->succeed(['stutas' =>1]);
                }else{
                  throw new  MenuNotExist(["msg"=>"菜品已经上架"]);
                }
            }else{
                if($menu->is_sold_out==1){
                    Menu::where("id",$id)->update(["is_sold_out"=>0]);
                    return $this->succeed(['stutas' =>0]);
                }else{
                  throw new  MenuNotExist(["msg" =>"菜品已经下架"]);
                }

            }
           
        }else{
            throw new MenuNotExist();
        }
    }
    public function delete(){
        $this->getToken();
        $id=input("param.id");
        if(strpos($id,",")){
            $ids=explode(",",$id);
            foreach ($ids as $id) {
                $menu=Menu::where("id",$id)->find();
                if(!empty($menu)){
                    Menu::get($id)->delete();
                }
            }
        }else{
            $menu=Menu::where("id",$id)->find();
            if(!empty($menu)){
                Menu::get($id)->delete();
            }
        }
        return $this->succeed(['msg' =>1]);
    }
    public function add(){
        $this->getToken();
        $param=input("param.");
        
        $id=empty($param["id"])?0:$param["id"];
        $menu=Menu::where("id",$id)->find();
        $data["meal_name"]=$param['name'];
        $data['descption']=$param['des'];
        $data['meal_price']=$param["price"];
        $data["merchant_id"]=$this->getId();
        $file = request()->file('image');
        $info = $file->move(ROOT_PATH . 'static' . DS . 'uploads');
        if($info){
          $image=$info->getSaveName();
          $image=str_replace("\\","/",$image);
          $data['picture']=config("host").$image;
        }else{
             throw new ImageException(["msg"=>$file->getError()]);
        }
        if(!empty($menu)){
            Menu::get($id)->update($data);
        }else{
           Menu::create($data);
        }
        return $this->succeed(['msg' =>true]);
     
        
    }
}