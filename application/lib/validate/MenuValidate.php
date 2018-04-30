<?php
namespace app\lib\validate;
class MenuValidate extends BaseValidate{
    protected $rule=[
        ["token" , "require|isExistToken","请求头为空|token无效或者已经过期"],
        ["merchant_id","require|isPositiveInteger","商户id必须填写|商户id必须是正整数"],
    ];
}