<?php
namespace app\merchant\model;
class Menu extends BaseModel{
    public function merchant(){
        return $this->hasOne("merchant","id","merchan_id")->field("merchant_id,id");
    }

  public function findMenus($goods,$nums,$field=""){
        $menus=[];
        if(is_array($goods))
        {
        for($i=0;$i<sizeof($goods);$i++){
            //$menu=new Menu();
           // $menu=$menu->where("id","=",$good)->find();
           $good=$goods[$i];
           if(empty($field))
           {
            $menu=$this->getMenuById($good);
           }else{
            $menu=$this->where("id","=",$good)->field($field)->find()->toArray();
           }
            if($menu){

                $this->addSale($good);
                $menu["nums"]=$nums[$i];
                array_push($menus,$menu);
            }
        }
        }else{
            $menu=$this->where("id","=",$goods)->field($field)->find();
            if($menu){
                $menu=$menu->toArray();
                $this->addSale($goods);
                $menu["nums"]=$nums;
                $menus=[$menu];
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