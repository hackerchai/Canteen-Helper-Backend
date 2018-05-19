<?php
namespace app\lib\validate;
class PhoneValidate extends BaseValidate{
    protected $rule=[
        ["token" , "require|isExistToken","请求头为空|token无效或者已经过期"],
        ["phone","require|isNotEmpty|isFrequent","电话号码必须填写|电话号码必须填写|已经发送验证码，请等待60s后再重新发送"],
    ];

}