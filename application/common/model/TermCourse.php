<?php
namespace app\common\model;
use think\Model;    // 导入think\Model类
/**
* TermCourse 
*/

class TermCourse extends Model
{
	public function Term()
	{
		return $this->belongsTo('Term');
	}

	public function Course()
	{
		return $this->belongsTo('Course');
	}
}