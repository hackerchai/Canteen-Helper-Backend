<?php
namespace app\business\controller;
use think\Controller;
use app\lib\success\Success;
use app\lib\validate\Token;
use think\Cache;
class BaseController extends Controller{
    protected function succeed($data){
        $se=new Success();
        return $se->create($data);
    }
    protected function getToken(){
        $tn=new Token();
        $param=$tn->goCheck();
        $token=$param['token'];
        return $token;
    }
    protected function getId(){
        $token=$this->getToken();
        $v=Cache::get($token);
        $v=json_decode($v,true);
        $id=$v['uid'];
        return $id;
    }
    protected function getValue($token){
        $v=Cache::get($token);
        $v=json_decode($v,true);
      
        return $v;
    }
}