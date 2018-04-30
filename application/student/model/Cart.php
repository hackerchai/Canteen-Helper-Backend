<?php
namespace app\student\model;
class Cart extends BaseModel{
    public function buy(){
        return $this->hasOne("student","id","buyId")->field("id,name");
    }
    public function menu(){
        return $this->hasOne("menu","id","menuId");
    }
    
}