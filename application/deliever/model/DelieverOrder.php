<?php
namespace app\deliever\model;


use think\Db;
class DelieverOrder extends BaseModel{

    public $user;

    public function __construct($token){
        $this->user = Db::name("student")->where("token",$token)->where("is_deliver",1)->find();

    }

    private function getSchedule(){
        $weekDay = date("w",time()) == 0 ? 7 : date("w",time());
        $hourNow = date("G",time());
        $mealTime = $hourNow<=10 ? "1" :($hourNow>=15 ? "3" :"2");
        $timeTable = Db::name("timetable")->where("uid",$this->user["id"])
        ->where("week_day",$weekDay)
        ->where("meal_time",$mealTime)
        ->select();
        $returnData = [];
        foreach ($timeTable as $key => $value) {
            $returnData[]=[$value["garden"],$value["building"]];
        }
        return $returnData[];
    }

    public function list($token){
        $avaliable = $this->getSchedule();
        $poentientalOrder = Db::name("order")->where("status",4)
        ->where("order_type",2)
        ->where("order_type",2)
        
        return $this->user;
    }
}