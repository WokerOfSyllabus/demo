<?php
namespace app\common\model;
use think\Model;    // 导入think\Model类
/**
* Student 学生表
*/

class Student extends Model
{
	/*** 一对多关联 
	* @author 梦云智 http://www.mengyunzhi.com * @DateTime 2016-10-24T16:27:05+0800 
	*/ 
	public function StudentCourses() 
	{ 
		return $this->hasMany('StudentCourse'); 
	}

	
}