<?php
namespace app\merchant\controller;
use app\merchant\model\MerchantMember;
use think\Controller;
use app\lib\validate\Token;
use think\Cache;
use app\lib\success\Success;
class BaseController extends Controller{
    protected function succeed($data){
        $se=new Success();
        return $se->create($data);
    }
    protected function getToken()
    {
        $tn=new Token();
        $param=$tn->goCheck();
        $token=$param['token'];
        return $token;
    }
    protected function getId(){
        $id=\app\service\Token::getVarByToken($this->getToken(),"uid");
        $merchant_id=MerchantMember::get($id)->toArray()["merchant_id"];
        return $merchant_id;
    }
}