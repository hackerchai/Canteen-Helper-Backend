<?php
namespace app\lib\validate;
class CodeValidate extends BaseValidate{
    protected $rule=[
        "code" => "require|isNotEmpty"
    ];
}