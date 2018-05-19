<?php
namespace app\lib\validate;
class MerchantIdValidate extends BaseValidate
{
    protected $rule = [
        ["token", "require|isExistToken", "请求头为空|token无效或者已经过期"],
        ["merchant_id", "require|isNotEmpty"],
    ];
}