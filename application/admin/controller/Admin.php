<?php
namespace app\admin\controller;
use app\admin\model\Admin as Admins;
use app\lib\validate\UpdateAdminValidate;
class Admin extends BaseController{
    public function index(){
        $admin=new Admins();
        $admin=$admin->where("id","=",$this->getSession("id"))->find()->toArray();
        $this->assign("admin",$admin);
        return $this->fetch();
    }
    public function update(){
        $admin=new Admins();
        $admin=$admin->where("id","=",$this->getSession("id"))->find()->toArray();
        $this->assign("admin",$admin);
        return $this->fetch();
    }
    public function updateMsg(){
        $id=$this->getSession("id");
        $admin=Admins::get($id);
        $param=(new UpdateAdminValidate())->checkData();
        $param['password']=md5($param['password']);
        $admin->save($param);
        return $this->redirect("index");
        
        
    }
>>>>>>> bbdbebe6ba5cf440c32e81b1f67ab43c77fe0f1a
}