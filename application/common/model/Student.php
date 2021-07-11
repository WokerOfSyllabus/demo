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
	public function Courses() 
	{ 
		return $this->belongsToMany('Course','student_course'); 
	}

	public function getIsChecked(Course &$Course)
	{
		//取id
		$studentId = (int)$this->id;  //修改
		$courseId= (int)$Course->id;

		//定制查询条件
		$map = array();
		$map['student_id'] = $studentId;
		$map['course_id'] = $courseId;

		//从关联表中取信息
		$StudentCourse = StudentCourse::get($map);
		if(is_null($StudentCourse)){
			return false;
		} else {
			return true;
		}
	}

	public function StudentCourses()
	{
		return $this->hasMany('StudentCourse');
	}
}