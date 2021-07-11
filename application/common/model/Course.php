<?php
namespace app\common\model;
use think\Model;
/**
 * 班级
 */
class Course extends Model
{
	public function Term()
	{
		return $this->belongsTo('Term');
	}
	/**
	 * 多对多关联
	 */
	public function Terms()
	{
		return $this->belongsToMany('Term', config('database.prefix') . 'term_course');
	}
	
	public function Students()
	{
		return $this->belongsToMany('Student', config('database.prefix') . 'student_course');
	}

	public function StudentCourses()
	{
		return $this->hasMany('StudentCourse');
	}
	/**
	 * 一对多关联
	 */
	public function TermCourses()
	{
		return $this->hasMany('TermCourse');
	}
}