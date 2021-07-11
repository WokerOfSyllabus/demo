<?php
namespace app\index\controller;
use think\Controller;    // 用于与V层进行数据传输
use app\common\model\Student;    // 学生模型
use app\common\model\StudentCourse;    // 学生课程表模型
use think\Request;    // 引用Request

class StudentController extends IndexController
{
	public function index()
	{
		// 获取查询信息
		$name = Request::instance()->get('name');



		// 设置每页大小
		$pageSize = 5;

		// 实例化Student
		$Student = new Student;

		// 按照条件查询数据并调用分页
		$students = $Student->where('name', 'like', '%' . $name . '%')->paginate($pageSize,false, [
			'query'=>[
				'name' => $name,
			],
		]);

		// 向v层传数据
		$this->assign('students',$students);

		// 取回打包后的数据
		$htmls = $this->fetch();

		// 将数据返回给用户
		return $htmls;

		// 获取查询信息
		$name = input('get.name');
	}

	public function insert()
	{
		$message = ''; // 提示信息
		try {
			// 接收传入数据
			$postData = Request::instance()->post();
			// 实例化Student空对象
		    $Student =new Student();

		    // 为对象赋值
		    $Student->number = $postData['number'];
		    $Student->name = $postData['name'];
		    $Student->sex = $postData['sex'];
		    $Student->telephone = $postData['telephone'];

		    $result = $Student->validate(true)->save();

		    // 反馈结果
		    if (false === $result) {
		    	// 验证未通过，发生错误
		    	$message = '新增失败：' . $Student->getError();
		    } else {
		    	// 提示操作成功，并跳转至学生管理列表
		    	return $this->success('新增成功。', $_POST['httpref']);
		    }

		    // 获取到ThinkPHP的内置异常时，直接向上抛出，交给ThinkPHP处理
		} catch (\think\Exception\HttpResponseException $e) {
			throw $e;

			// 获取到正常的异常时，输出异常
		} catch (\Exception $e) {
			// 发生异常
			return $e->getMessage();
		}

		return $this->error($message);
		
	}

	public function add()
	{
		// 实例化
		$Student = new Student;

		// 设置默认值
		$Student->id = 0;
		$Student->number = '';
		$Student->name = '';
		$Student->sex = 0;
		$Student->telephone = '';
		$this->assign('Student', $Student);


		// 调用edit模板
		// return $this->fetch('edit');
	    
		$htmls = $this->fetch();
		return $htmls;

		
	}

	public function delete()
	{
		// 实例化请求类
		// $Request = Request::instance();
		// 获取pathinfo传入的ID值.
		$id = Request::instance()->param('id/d'); // "/d"表示将数值转化为“整形”
		// var_dump($id);

		if (is_null($id) || 0 === $id) {
			return $this->error('未获取到ID信息');
		}

		// 获取要删除的对象
		$Student = Student::get($id);

		// 要删除的对象不存在
		if (is_null($Student)) {
			return $this->error('不存在id为' . $id . '的班级，删除失败');
		}

			// 删除对象
			if (!$Student->delete()) {
				return $this->error('删除失败：' . $Student->getError());
			}

			// 进行跳转
			return $this->success('删除成功');	

			// 删除原有信息 
			$map = ['number'=>$id]; 

			// 执行删除操作。由于可能存在 成功删除0条记录，故使用false来进行判断，而不能使用 
			// if (!KlassCourse::where($map)->delete()) { 
			// 我们认为，删除0条记录，也是成功 
			if (false === $Student->StudentCourses()->where($map)->delete()) { return $this->error('删除学生课程关联信息发生错误' . $Student->StudentCourses()->getError()); 
		    }
		/*
		try {
			// 实例化请求类
			$Request = Request::instance();

			// 获取get数据
			$id = Request::instance()->param('id/d'); // “/d”表示将数据转化为“整形”
			var_dump($id);

			// 判断是否成功接收
			if (0 === $id) {
				throw new \Exception("未获取到ID信息", 1);
			}

			// 获取要删除的对象
			$Student = Student::get($id);

			// 要删除的对象存在
			if (is_null($Student)) {
				throw new \Exception('不存在id为' . $id . '的学生，删除失败', 1);
			}

			// 删除对象
			if (!$Student->delete() ) {
				return $this->error('删除失败:' . $Student->getError());
			}

			/*

			// 删除原有信息 
			$map = ['number'=>$id]; 

			// 执行删除操作。由于可能存在 成功删除0条记录，故使用false来进行判断，而不能使用 
			// if (!KlassCourse::where($map)->delete()) { 
			// 我们认为，删除0条记录，也是成功 
			if (false === $Student->StudentCourses()->where($map)->delete()) { return $this->error('删除学生课程关联信息发生错误' . $Student->StudentCourses()->getError()); 
		    }
		    

		// 获取到ThinkPHP的内置异常时，直接向上抛出，交给ThinkPHP处理
		} catch (\Exception $e) {
			return $e->getMessage();
		}

		// 进行跳转
		return $this->success('删除成功', $Request->header('referer'));
		*/
	}


	public function edit ()
    {
        $id = Request::instance()->param('id/d');
        //dump($id);
        if(is_null($Student = Student::get($id))){
            return '系统未找到ID为' . $id . '的记录';
        }
        $this->assign('Student', $Student); 
        $htmls =  $this->fetch();
        return $htmls;
    }
    public function update()
    {
        /*
        $id = Request::instance()->post('id/d');
        $Admin = Admin::get($id);
        if (is_null($Admin)) {
            return $this->error('系统未找到ID为' . $id . '的记录');
        }
        //$Course->name = Request::instance()->post('name');       
        if (!$Admin->validate()->save($Admin->getData())) { 
            return $this->error('更新错误：' . $Admin->getError());
        } else {
            return $this->success('编辑成功', $_POST['httpref']);
            //return $this->success('操作成功', url('index'));
        }*/
        $student = Request::instance()->post(); 
        $Student = new Student(); 
        //dump($admin['httpref']);
        $message = '更新成功'; 
        // 依据状态定制提示信息 
        if (false === $Student->validate(true)->isUpdate(true)->save($student)) { 
            return  $this->error($message = '更新失败：' . $Student->getError(),$_POST['httpref']);
            //$message = '更新失败：' . $Admin->getError(); 
        } else {
            return  $this->success('编辑成功',$_POST['httpref']);
        }
    }   
        //return $message;
        //return $this->success('更新成功', url('index'));
        //$this->success('编辑成功', $_POST['httpref']);


	/*public function edit()
	{
		 $id = Request::instance()->param('id/d');

        // 判断是否存在当前记录

        if (is_null($Student = Student::get($id))) {
            return $this->error('未找到ID为' . $id . '的记录');
        }

        $this->assign('Student', $Student);
        return $this->fetch();

		try {
			// 获取传入ID
			$id = Request::instance()->param('id/d');


			// 判断是否成功接收
			if (is_null($id) || 0 ===$id) {
				throw new \Exception('未获取到ID信息', 1);			
			}

			// 在Student表模型中获取当前记录
			if (null ===$Student = Student::get($id)) {
				// 由于在$this->error抛出了异常，所以也可以省略return（不推荐）
				$this->error('系统未找到ID为' . $id . '的记录');
		    }

		    // 将数据传给v层
		    $this->assign('Student', $Student);

		    // 获取封装好的v层内容
		    $htmls = $this->fetch();

		    // 将封装好的v层内容返回给用户
		    return $htmls;

		// 获取到ThinkPHP的内置异常时，直接向上抛出，交给ThinkPHP处理
		} catch (\think\Exception\HttpResponseException $e) {
			throw $e;

		// 获取到正常的异常时，输出异常	
		} catch (\Exception $e) {
			return $e->getMessage();
		}
	}*/


	// public function update()
	// {
	// 	$id = Request::instance()->post('id/d');

 //        // 获取传入的班级信息
 //        $Student = Student::get($id);
 //        if (is_null($Student)) {
 //            return $this->error('系统未找到ID为' . $id . '的记录');
 //        }

 //        // 数据更新
 //        $Student->name = Request::instance()->post('name');       
 //        $Student->number = Request::instance()->post('number');
 //        $Student->sex = Request::instance()->post('sex');
 //        $Student->telephone = Request::instance()->post('telephone');
        

 //        if($Student->validate()->save())
 // 		{
 // 			return $this->success('操作成功', url('index'));
 // 		}else{
 // 			return $this->error('操作失败', url('index'));
 // 		}
           
            
        

	// 	// 接收数据，取要更新的关键字信息
	// 	$id = Request::instance()->post('id/d');
	// 	// var_dump($number);

	// 	// 获取当前对象
	// 	$Student = Student::get($id);
	// 	// var_dump($Student);
	// 	// $test=!is_null($Student);
	// 	// var_dump($test);

	// 	//$Student->id = input('post.id');
	
	// 	$Student->number = input('post.number');
	// 	$Student->name = input('post.name');
		
	
	// 	$Student->sex = input('post.sex/d');
	// 	$Student->telephone = input('post.telephone');
	
	// 	// 更新或保存
		

	// 	if (!is_null($Student)) {
	// 		if (!($Student->validate(true)->save($Student->getData()))) {
	// 			return $this->error('操作失败' . $Student->getError());
	// 		}
	// 	} else {
	// 		return $this->error('当前操作的记录不存在');
	// 	}
	// 	// 成功跳转至index触发器
	// 	return $this->success('编辑成功', $_POST['httpref']);
	// }

	/**
	* 对数据进行保存或更新
	* @param Student    &$Student  学生
	* @return    bool
	* @author 梦云智 http://www.mengyunzhi.com
	* @DateTime 2016-10-24T15:24:29+0800
	*/
	/*private function saveStudent(Student &$Student, $isUpdate = true)
	{
		// 写入要更新的数据
		$Student->id = input('post.id');
		if (!$isUpdate) {
			$Student->number = input('post.number');
			$Student->name = input('post.name');
		}
	
		$Student->sex = input('post.sex/d');
		$Student->telephone = input('post.telephone');
	
		// 更新或保存
		return $Student->validate(true)->save();
	}

	public function save()
	{
		// 实例化
		$Student = new Student;


		// 新增数据
		if (!$this->saveStudent($Student)) {
			return $this->error('操作失败' . $Student->getError());
		}

		// 成功跳转至index触发器
		return $this->success('新增成功', $_POST['httpref']);
	}*/

	public function own()
	{
		$user_name = Request::instance()->param('user_name');

		// 获取所有的学生信息
		$students = Student::all();
		$this->assign('students', $students);
	
		// 获取用户操作的学生信息
		if (false === $Student = Student::get($user_name)) {

			return $this->error('系统未找到用户名为' . $user_name . '的记录');
		}

		$this->assign('Student', $Student);
		return $this->fetch();

	}




}