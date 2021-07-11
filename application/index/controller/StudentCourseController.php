<?php
namespace app\index\controller;
use app\common\model\Student;
use app\common\model\Course;
use app\common\model\StudentCourse;

use think\Controller;
use think\Request;
use think\Db;

class StudentCourseController extends Controller
{
	public function index()
	{
		$name = Request::instance()->get('stu_name');
		//echo $number;
		$pageSize = 5;

		//实例化
		$StudentCourse = new StudentCourse;

		//定制查询信息
		if (!empty($name)) {
			$StudentCourse->Student->where('name', $name);
		}
		//按条件查询数据并调用分页
		$studentcourses = $StudentCourse->paginate($pageSize, false, [
			'query'=>[
				'stu_name' => $name,
			],
		]);


		//向V层传数据
		$this->assign('studentcourses',$studentcourses);
		//取回打包后数据
		$htmls = $this->fetch();
		//将数据返回用户
		return $htmls;
	}

	/**
	 * 增加
	 */
	public function add()
	{
		//$courses = Course::all();
		//$studentcourses = StudentCourse::all();

		//$this->assign('courses',$courses);
		
		$this->assign('Student',new Student);
		return $this->fetch();

	}
	public function save()
	{
		$Request = Request::instance();
		$name = $Request->post('stu_name');
		
		$stuId = Db::table('student')->where('number',$Request->post('stu_num'))->value('id');
		$stuName = Db::table('student')->where('number',$Request->post('stu_num'))->value('name');
		
		if (!($name === $stuName)) {
			return $this->error('学号与姓名不对应',url('add'));
		}

		//接收course_id这个数组
		$courseIds = $Request->post('course_id/a');  // /a表示获取的类型为数组

		//利用course_id这个数组，拼接包括student_id和course_id的二位数组
		if (!is_null($courseIds)) {
			$datas = array();
			foreach ($courseIds as $courseId){
				$data = array();
				$data['course_id'] = $courseId;
				$data['student_id'] =  $stuId;
				array_push($datas, $data);
			}
		    // var_dump(array_push);
		    // return;

		    //利用saveAll()
		    if (!empty($datas)) {
		    	$StudentCourse = new StudentCourse;
		    	if (!$StudentCourse->validate(true)->saveAll($datas)) {
		    		return $this->error('课程-班级信息保存错误：' . $StudentCourse->getError());
		    	}
			//unset($StudentCourse);
		   }
		}
		return $this->success('操作成功', url('index'));

	}

	/**
	 * 更新
	 */
	public function edit()
	{
		$id = Request::instance()->param('id/d');
		$Student = Student::get($id);

		if (is_null($Student)) {
			return $this->error('不存在ID为' . $id . '的记录');
		}

		$this->assign('Student', $Student);
		return $this->fetch();
	}

	public function update()
	{
		//获取当前学生
		$id = Request::instance()->post('id/d');
		if (is_null($Student = Student::get($id))) {
			return $this->error('不存在ID为' .$id. '的记录');
		}

		//删除原有信息
		$map = ['student_id'=>$id];
		//$map = ['course_id'=>];
		if (false === $Student->StudentCourses()->where($map)->delete()) {
			return $this->error('删除学生课程关联信息发生错误' . $Student->StudentCourses()->getError());
		}
		
		//增加新增数据，执行添加操作
		$courseIds = Request::instance()->post('course_id/a');
		if (!is_null($courseIds)) {
			if (!$Student->Courses()->saveAll($courseIds)) {
				return $this->error('学生—班级信息保存错误' . $Student->Courses()->getError());
			}
		}
		return $this->success('更新成功', url('index'));
	}

	/**
	 * 删除
	 */
	public function delete()
	{
		//获取pathinfo传入的ID值
		$id = Request::instance()->param('id/d');  //"/d"表示将数值转化为整形

		if(is_null($id) || 0 === $id){
			return $this->error('未获取到ID信息');
		}

		//获取要删除的对象
		$StudentCourse = StudentCourse::get($id);

		//要删除对象不全在
		if(is_null($StudentCourse)){
			return $this->error('不存在id为' . $id . '的信息，删除失败');
		}

		//删除对象
		if(!$StudentCourse->delete()){
			return $this->error('删除失败' . $StudentCourse->getError());
		}

		//进行跳转
		return $this->success('删除成功',url('index'));
	}
}

