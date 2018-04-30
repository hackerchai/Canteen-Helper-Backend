<?php
namespace app\lib\validate;
class RegisterValidate extends BaseValidate{
    protected $rule=[
        ["token" , "require|isExistToken","请求头为空|token无效或者已经过期"],
        ["phone","require|isNotEmpty","电话号码必须填写|电话号码必须填写"],
        ["code","require|isNotEmpty|isSmsCode","验证码必须填写|验证码必须填写|已经发送验证码，请等待5分钟后再重新发送"],
    ];
    protected function isSmsCode($value,$rule='',$data='',$field=''){
        
        $code=cache($data["phone"]);
        
        if($code==$value){
            return true;
        }else{
            return "验证码错误";
        }

    }
}