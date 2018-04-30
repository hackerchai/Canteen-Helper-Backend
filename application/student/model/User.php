<?php
namespace app\student\model;
class User extends BaseModel{
    public static function getUserByOpenId($openId){
        $user=self::where("open_id","=",$openId)->find();
        return $user;
    }
}