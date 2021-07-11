<?php
namespace app\common\validate;
use think\Validate;     // 内置验证类
class Admin extends Validate
{
    protected $rule = [
         'username' => 'require|unique:admin|length:2,25',
    ];
}
