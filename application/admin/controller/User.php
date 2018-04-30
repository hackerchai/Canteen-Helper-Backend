<?php
namespace app\admin\controller;
use app\admin\model\Student;
use app\lib\exception\AddressNotExist;
use app\admin\model\Admin;
use app\lib\db\Select;
class User  extends BaseController         
{
   
    
    public function index()
    {
        $users=Student::paginate(1);
        $show=$users->render();
        $this->assign('show',$show);
        $this->assign('users',$users);
        return $this->fetch('user\user');
     
    }
    public function sourchAdin(){
        $param=input("post.");
        $admin=new Admin();
        $select=new Select($admin);
        $admins=$select->blurryQuery($param['table_search']);
        $this->assign("admins",$admin);
        $this->assign('show','');
        return $this->fetch("role");

    }
    public function sourchUser(){
        $param=input("post.");
        $user=new Users();
        $select=new Select($user);
        $users=$select->blurryQuery($param['table_search']);
        $this->assign('users',$users);
        $this->assign('show',"");
        return $this->fetch('user');
        

    }
    public function role()
    {
        $admins=Admin::paginate(1);
        $show=$admins->render();
        $this->assign('show',$show);
        $this->assign('admins',$admins);

        return $this->fetch();
    }
}
