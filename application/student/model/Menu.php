<?php
namespace app\student\model;
class Menu extends BaseModel{
  public function findMenus($goods,$field=""){
        $menus=[];
        if(is_array($goods))
        {
        foreach($goods as $good){
            //$menu=new Menu();
           // $menu=$menu->where("id","=",$good)->find();
           if(empty($field))
           {
            $menu=$this->getMenuById($good);
           }else{
               $menu=$this->where("id","=",$good)->field($field)->find()->toArray();
           }
            if($menu){
                $this->addSale($good);
                array_push($menus,$menu);
            }
        }
        }else{
            $menu=$this->getMenuById($goods);
            if($menu){
                $this->addSale($goods);
                $menus=$menu;
            }
            
        }
        return $menus;
    }
    public  function getMenuById($id){
        $menu=Menu::get($id);
        if(!empty($menu)) {
             $menu=$menu->toArray(); 
            return $menu;
        }else{
            return false;
        }
    }
    public function addSale($id){
        $menu=$this->getMenuById($id);
        $sale=$menu["sales_count"];
        $sale++;
        $menu=Menu::get($id);
        $menu->save(["sales_count" => $sale]);

    }
}