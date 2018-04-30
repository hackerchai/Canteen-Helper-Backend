<?php
namespace app\admin\controller;
use think\Controller;
use think\Session;
use app\lib\dialogue\NotAdmin;
class BaseController extends Controller{
   protected $beforeActionList =[
       'before',
   ];
   public function before(){
        $type=Session::get('id');
        if(empty($type))
        {
            $notAdmin=new NotAdmin();
            $notAdmin->show();
        }
   }
   public function getSession($name)
   {
       
      return Session::get($name);
   }
   
}