<?php
namespace app\lib\validate;
class CodeValidate extends BaseValidate{
    protected $rule=[
        ["code" , "require|isNotEmpty","请求为空|请求为空"]
    ];
}