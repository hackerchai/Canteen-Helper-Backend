<?php
namespace app\lib\validate;
class PhoneValidate extends BaseValidate{
    protected $rule=[
        ["token" , "require|isExistToken","请求头为空|token无效或者已经过期"],
        ["phone","require|isNotEmpty","电话号码必须填写|电话号码必须填写"],
    ];
}