<?php 
namespace app\common\validate; 
use think\Validate; // 内置验证类 

class Student extends Validate 
{ 
     protected $rule = [ 
     	// 'id' => 'require',
     	'student_number' => 'require|unique:student|length:4,25',
     	'user_name' => 'require|unique:student|length:4,25',
     	'student_name' => 'require|length:2,25', 
     	'sex' => 'in:0,1',
     	'telephone' => 'require|length:11', 
     	'email' => 'email', 
     ]; 
}