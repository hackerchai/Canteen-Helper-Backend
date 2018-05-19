<?php
namespace app\lib\validate;
class AddressValidate extends BaseValidate{
    protected $rule=[
        ["token" , "require|isExistToken","请求头为空|token无效或者已经过期"],
        ["garden","require|length:1"],
        ["building","require|length:1"],
        ["room","require|length:3"],
        ["phone","require|length:6,15"],
        ["user_name","require"],
    ];
}