<?php
namespace app\lib\validate;
class OrderIdValidate extends BaseValidate{
    protected $rule=[
        ["token" , "require|isExistToken","请求头为空|token无效或者已经过期"],
        ["order_id","require|length:18"],
    ];
}