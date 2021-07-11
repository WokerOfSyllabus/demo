<?php
namespace app\common\validate;
use think\Validate;//内置验证类

class Dingding extends validate
{
	protected $rule=[
		'dingding_url'=>'require|url',
		'send_time_hour'=>'require|number|between:0,23',
		'send_time_minute'=>'require|number|between:0,59',
	];
}