<?php
namespace app\common\model;
use think\Model;
/**
 * 课表
 */
class StudentCourse extends Model
{
	/**
	 * ThinkPHP使用一个叫做__get()的魔法函数来完成这个函数的自动调用
	 */
	public function Courses()
	{
		//定义两个表的关联
		return $this->belongsTo('Course');
	}
	public function Student() 
    	{
        		return $this->belongsTo('Student'); 
    	}

    	public function Course() 
    	{
        		return $this->belongsTo('Course'); 
    	}
}