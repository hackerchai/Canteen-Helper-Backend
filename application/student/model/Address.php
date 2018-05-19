<?php
namespace app\student\model;
class Address extends BaseModel{
    public function getGardenAttr($value){
      
        $garder=[1 => "梅园" ,2 => "竹园", 3 =>"松园", 4 => "兰园"];
      
        return $garder[$value];
    }
    public function getBuildingAttr($value){
       
        $garder=[1 => "一栋",2 => "二栋", 3 => "三栋", 4 => "四栋",
        5 => "五栋", 6 => "六栋", 7 => "七栋", 8 => "八栋",  9 => "九栋"];
        return $garder[$value];
    }
    public function getAddressByUid($uid){
        $address=$this->where("uid" ,"=",$uid)->select();
        $addr=[];
        if(!empty($address)){
            foreach($address as $a){
                array_push($addr,$a);
            }
        }

        return $addr;
    }
    
}