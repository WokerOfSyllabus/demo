<?php
namespace app\index\controller;
use app\common\model\Course; //课程
use app\common\model\Term;//学期信息
use think\Request;

class CourseController extends IndexController
{ 
    public function index() 
    { 

        //获取所有学期信息
        $terms = Term::all();
        $this->assign('terms',$terms);

        
        // 获取查询信息
        $name = Request::instance()->get('name');
        // echo $name;

        $pageSize = 5; // 每页显示5条数据

        $Course = new Course;

        // 打印$Course 至控制台
        trace($Course, 'debug');

        // 按条件查询数据并调用分页
        $courses = $Course->where('name', 'like', '%' . $name . '%')->paginate
        ($pageSize, false, [
            'query'=>[
                'name' => $name,
            ],
        ]);

        // 向V层传数据
        $this->assign('courses', $courses); 

        // 取回打包后的数据
        $htmls = $this->fetch();

        // 将数据返回给用户
        return $htmls;


        /*$pageSize = 5; // 每页显示5条数据 
        $Course = new Course; 
        $courses = $Course->paginate($pageSize);
        $this->assign('courses', $courses); 
        // 取回打包后的数据 
        $htmls = $this->fetch(); 
        // 将数据返回给用户 
        return $htmls;*/
    }

    public function add() 
    { 
        //获取所有学期信息
        $terms = Term::all();
        $this->assign('terms',$terms);
        return $this->fetch();
    } 

    public function save()
    {
        //存课程信息
        $Course = new Course();
        $Course->name = Request::instance()->post('name');       
        /*$Course->start_week = Request::instance()->post('start_week');
        $Course->end_week = Request::instance()->post('end_week');
        $Course->day_week = Request::instance()->post('day_week');
        $Course->period = Request::instance()->post('period');
        $Course->type_week = Request::instance()->post('type_week');
        $Course->type = Request::instance()->post('type');
        $Course->term_id = Request::instance()->post('term_id');*/
        //新增数据并验证
        /*if (!$Course->save()){
            return $this->error('保存错误：'.$Course->getError());
        }
        
        unset($Course);//unset出现的位置和new语句的缩进量相同，在返回前，最后被执行。

        return $this->success('添加成功', $_POST['httpref']);
        // return $this->success('操作成功', url('index'));*/

         if (!$Course->validate()->save($Course->getData())) { 
            return $this->error('增加错误：' . $Course->getError());
        } else {
            return $this->success('增加成功', $_POST['httpref']);
            /*return $this->success('操作成功', url('index'));*/
        }
    }

    public function edit ()
    {

        //获取所有学期信息
        $terms = Term::all();
        $this->assign('terms',$terms);

        $id = Request::instance()->param('id/d');

        // 判断是否存在当前记录

        if (is_null($Course = Course::get($id))) {
            return $this->error('未找到ID为' . $id . '的记录');
        }

        $this->assign('Course', $Course);
        return $this->fetch();
    }

    public function update()
    {

        //获取所有学期信息
        $terms = Term::all();
        $this->assign('terms',$terms);
        
        $id = Request::instance()->post('id/d');

        // 获取传入的班级信息
        $Course = Course::get($id);
        if (is_null($Course)) {
            return $this->error('系统未找到ID为' . $id . '的记录');
        }

        // 数据更新
        $Course->name = Request::instance()->post('name');       
        /*$Course->start_week = Request::instance()->post('start_week');
        $Course->end_week = Request::instance()->post('end_week');
        $Course->day_week = Request::instance()->post('day_week');
        $Course->period = Request::instance()->post('period');
        $Course->type_week = Request::instance()->post('type_week');
        $Course->type = Request::instance()->post('type');
        $Course->term_id = Request::instance()->post('term_id');
*/
        if (!$Course->validate()->save($Course->getData())) { 
            return $this->error('更新错误：' . $Course->getError());
        } else {
            return $this->success('更新成功', $_POST['httpref']);
            /*return $this->success('操作成功', url('index'));*/
        }
    }


    public function delete()
    {
        try {
            // 获取get数据
            $Request = Request::instance();
            $id = Request::instance()->param('id/d');

            // 判断是否成功接收
            if (0 === $id) {
                throw new \Exception("未获取到ID信息", 1);
            }
            
            // 获取要删除的对象
            $Course = Course::get($id);
            
            // 要删除的对象存在
            if (is_null($Course)) {
                throw new \Exception('不存在id为' . $id . '的课程，删除失败', 1);
            }
            // 删除对象
            if (!$Course->delete()) {
                return $this->error('删除失败:' . $Course->getError());
            }

            //获取到ThinkPHP的内置异常时，直接向上抛出，交给ThinkPHP处理
        } catch (\think\Exception\HttpResponseException $e) {
            throw $e;

            // 获取到正常的异常时，输出异常
        } catch (\Exception $e) {
            return $e->getMessage();
        }

            // 进行跳转
        return $this->success('删除成功', $Request->header('referer'));
        
    }

    public function selectTime()
    {
        $id = Request::instance()->param('id/d');

        // 判断是否存在当前记录

        if (is_null($Course = Course::get($id))) {
            return $this->error('未找到ID为' . $id . '的记录');
        }

        $this->assign('Course', $Course);
        return $this->fetch();
    }

    public function selectWeek()
    {
        $id = Request::instance()->param('id/d');

        // 判断是否存在当前记录

        if (is_null($Course = Course::get($id))) {
            return $this->error('未找到ID为' . $id . '的记录');
        }

        $this->assign('Course', $Course);
        return $this->fetch();
    }
}