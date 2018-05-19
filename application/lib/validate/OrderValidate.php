<?php
namespace app\lib\validate;
class OrderValidate extends BaseValidate{
    protected $rule=[
        ["token" , "require|isExistToken","请求头为空|token无效或者已经过期"],
        ["goods","require|isNotEmpty"],
        ["nums","require|isNotEmpty"],
        ["order_type","require|isNotEmpty"],
    ];
}