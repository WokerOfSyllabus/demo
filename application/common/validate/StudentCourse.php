<?php
namespace app\common\validate;
use think\Validate;

class StudentCourse extends Validate
{
	protected $rule = [
		'student_id' => 'require',
		'course_id' => 'require'
	];
}