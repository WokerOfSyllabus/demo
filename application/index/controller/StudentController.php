<?php
namespace app\index\controller;
use think\Controller;    // 用于与V层进行数据传输
use app\common\model\Student;    // 学生模型
use think\Request;    // 引用Request

class StudentController extends Controller
{
	public function index()
	{
		// 获取查询信息
		$studentName = Request::instance()->get('studentName');

		// 设置每页大小
		$pageSize = 5;

		// 实例化Student
		$Student = new Student;

		// 按照条件查询数据并调用分页
		$students = $Student->where('studentName', 'like', '%' . $studentName . '%')->paginate($pageSize,false, [
			'query'=>[
				'studentName' => $studentName,
			],
		]);

		// 向v层传数据
		$this->assign('students',$students);

		// 取回打包后的数据
		$htmls = $this->fetch();

		// 将数据返回给用户
		return $htmls;

		// 获取查询信息
		$studentName = input('get.studentName');
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
		    $Student->studentName = $postData['studentName'];
		    $Student->sex = $postData['sex'];
		    $Student->username = $postData['username'];
		    $Student->email = $postData['email'];
		    $Student->create_time = $postData['create_time'];

		    $result = $Student->validate(true)->save();

		    // 反馈结果
		    if (false === $result) {
		    	// 验证未通过，发生错误
		    	$message = '新增失败：' . $Student->getError();
		    } else {
		    	// 提示操作成功，并跳转至学生管理列表
		    	return $this->success('用户' . $Student->studentName . '新增成功。', url('index'));
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
		$Student->studentId = 0;
		$Student->studentName = '';
		$Student->username = '';
		$Student->sex = 0;
		$Student->email = '';
		$this->assign('Student', $Student);


		// 调用edit模板
		return $this->fetch('edit');
	    /*
		$htmls = $this->fetch();
		return $htmls;
		*/
	}

	public function edit()
	{
		try {
			// 获取传入ID
			$studentId = Request::instance()->param('studentId/d');

			// 判断是否成功接收
			if (is_null($studentId) || 0 ===$studentId) {
				throw new \Exception('未获取到ID信息', 1);			
			}

			// 在Student表模型中获取当前记录
			if (null ===$Student = Student::get($studentId)) {
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
	}

	public function update()
	{
		// 接收数据，取要更新的关键字信息
		$studentId = Request::instance()->post('studentId/id');

		// 获取当前对象
		$Student = Student::get($studentId);

		if (!is_null($Student)) {
			if (!$this->saveStudent($Student, true)) {
				return $this->error('操作失败' . $Student->getError());
			}
		} else {
			return $this->error('当前操作的记录不存在');
		}
		// 成功跳转至index触发器
		return $this->success('操作成功', url('index'));
	}

	/**
	* 对数据进行保存或更新
	* @param Student    &$Student  学生
	* @return    bool
	* @author 梦云智 http://www.mengyunzhi.com
	* @DateTime 2016-10-24T15:24:29+0800
	*/
	private function saveStudent(Student &$Student, $isUpdate = false)
	{
		// 写入要更新的数据
		$Student->studentName = input('post.studentName');
		if (!$isUpdate) {
			$Student->username = input('post.username');
		}
		$Student->sex = input('post.sex/d');
		$Student->email = input('post.email');

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
		return $this->success('操作成功', url('index'));
	}
}