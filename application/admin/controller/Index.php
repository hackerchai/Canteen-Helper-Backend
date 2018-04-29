<?php
namespace app\admin\controller;
use app\lib\exception\ParamerException;
use app\lib\validate\IDMustBeInt;
use app\lib\email\Email;
use app\lib\exception\Add;
use app\lib\db\Select;
use app\lib\ioc\Factory;
use app\admin\model\Admin;
class Index  extends BaseController         
{
    protected $beforeActionList =[
        'before' => ['except' => 'index'],    
    ];
    public function index()
    {
        return $this->fetch('login/index');
     
    }
    public function login()
    {
        return $this->fetch('index/index');
    }
    public function test(){
        //$factroy=Factory::getFactory();
        //throw new Add(["msg" =>"dsjfjkjkfjk"]);
        return ['msg'=>"sdffd"];
       
    }
    public function holle(){
       
      
        return $this->fetch();
    }
}
