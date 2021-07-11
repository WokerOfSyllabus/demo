<?php
namespace app\index\controller;
use app\common\model\TermCourse;    // 学期课程表模型
use app\common\model\Course; //课程
use app\common\model\Term;//学期信息

use think\Db;
use think\Request;    // 引用Request

class TermCourseController extends IndexController
{
	public function index()
	{
		// 获取查询信息
		$id = Request::instance()->get('id');

		$TermCourse = new TermCourse;

		// 向v层传数据
		$this->assign('TermCourse',$TermCourse);

		// 取回打包后的数据
		$htmls = $this->fetch();

		// 将数据返回给用户
		return $htmls;

		
	}

	 public function selectTime()
    {

         // 获取查询信息
        $id = Request::instance()->param('id/d');   //course_id
        // echo $name;

        $pageSize = 5; // 每页显示5条数据

        $Course = new Course;

        

        $TermCourse = TermCourse::get($id);

        // 打印$Course 至控制台
        trace($Course, 'debug');

        // 按条件查询数据并调用分页
        $termCourses = $TermCourse->where('course_id', 'like', '%' . $id . '%')->paginate
        ($pageSize, false, [
            'query'=>[
                'id' => $id,
            ],
        ]);
        $this->assign('termCourses', $termCourses);
        


        // $id = Request::instance()->param('id/d');

        // 获取所有学期信息
        $Course = Course::get($id);
        $this->assign('Course',$Course);

        // 判断是否存在当前记录

        if (is_null($TermCourse = TermCourse::get($id))) {
            return $this->error('未找到ID为' . $id . '的记录');
        }

        $this->assign('TermCourse', $TermCourse);

        return $this->fetch();
    }

	public function selectWeek()
    {
        

        // 获取所有学期信息
        $terms = Term::all();
        $this->assign('terms',$terms);

        $id = Request::instance()->param('id/d');

        // 获取所有学期信息
        $Course = Course::get($id);
        $this->assign('Course',$Course);


        // 判断是否存在当前记录

        $TermCourse = TermCourse::get($id);

        $TermCourse->week_day = Request::instance()->param('week_day/d');

        $this->assign('TermCourse', $TermCourse);
        return $this->fetch();
    }

    public function add() 
    { 
        //获取所有学期信息
        $TermCourse = TermCourse::all();
        $this->assign('TermCourse',$TermCourse);
        return $this->fetch();
    } 

    public function save()
    {
        //存课程信息
        $TermCourse = new TermCourse();
        $TermCourse->course_id = Request::instance()->post('id');  
        $TermCourse->term_id = Request::instance()->post('term_id'); 
        // $TermCourse->week = Request::instance()->post('week');      
        $TermCourse->week_day = Request::instance()->post('week_day'); 
        $TermCourse->period = Request::instance()->post('period'); 
        
        //新增数据并验证
        /*if (!$TermCourse->save()){
            return $this->error('保存错误：'.$TermCourse->getError());
        }*/
        
        // -------------------------- 新增课程周次信息 --------------------------
        // 接收week这个数组
        $weeks = Request::instance()->post('week/a'); // /a表示获取的类型为数组
        //dump($weeks);
        if (!is_null($weeks)) {
            $datas = array();
            foreach ($weeks as $week) {
                $data = array();
                $data['week'] = $week;
                $data['course_id'] = $TermCourse->Course->id; 
                $data['term_id'] = $TermCourse->Term->id; 
                $data['week_day'] = $TermCourse->week_day; 
                $data['period'] = $TermCourse->period; 
               // $data['course_id'] = $Course->id; // 此时，由于前面已经执行过数据插入操作，所以可以直接获取到Course对象中的ID值。
                array_push($datas, $data);
            }
            // 利用saveAll()方法，来将二维数据存入数据库。
            if (!empty($datas)) {
                $TermCourse = new TermCourse;
                if (!$TermCourse->validate(true)->saveAll($datas)) {
                    return $this->error('课程-班级信息保存错误：' . $TermCourse->getError());
                }   

                unset($TermCourse); // unset出现的位置和new语句的缩进量相同，最后被执行
            }
        }
            // -------------------------- 新增班级课程信息(end) --------------------------

        unset($TermCourse);//unset出现的位置和new语句的缩进量相同，在返回前，最后被执行。

        return $this->success('添加成功', $_POST['httpref']);
        // return $this->success('操作成功', url('index'));
    }
}