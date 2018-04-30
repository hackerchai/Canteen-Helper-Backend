<?php
namespace app\admin\model;
class Student extends BaseModel{
    public function getIsDeliverAttr($value){
        $static=[
            0=>"是",
            1=>"否",
        ];
        return $static[$value];
    }
}