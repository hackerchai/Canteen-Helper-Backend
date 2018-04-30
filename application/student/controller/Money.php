<?php
namespace app\student\controller;
use app\student\model\Student;

class Money extends BaseController{
    public function deposit(){
        $id=$this->getId();
        $res=Student::where("id","=",$id)->find()->toArray();
        $deposit=$res['deposit'];
        $msg=$this->isDeliver($deposit);
        return $this->succeed(["mag" =>$msg]);
        
    }
    private function isDeliver($current){
        $res=db("setting")->where("setting_key","deposit")->find();
        $money=$res['setting_key'];
        if($current<=$money)
        {
            return false;
        }else{
            return true;
        }
    }
}