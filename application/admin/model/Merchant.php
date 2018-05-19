<?php
namespace app\admin\model;
class Merchant extends BaseModel{
    public function getIsVerifiedAttr($value){
        $state=[
            0=>"已经通过",
            1 =>"未通过"
        ];
        return $state[$value];
    }
}