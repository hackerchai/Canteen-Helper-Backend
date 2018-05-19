<?php
namespace app\lib\validate;
class CodeValidate extends BaseValidate{
    protected $rule=[
        ["code" , "require|isNotEmpty","code请求为空|code请求为空"]
    ];
}