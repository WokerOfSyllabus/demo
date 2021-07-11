<?php
namespace app\index\controller;
use app\common\model\Course; // 课程
use app\common\model\StudentCourse;// 课表
use app\common\model\Term;// 学期信息
use app\common\model\Student;// 学生信息
use app\common\model\Dingding;// 钉钉信息
use app\common\dingding\DingdingSdk;// 钉钉SDK
use think\Request;

class DingdingController extends IndexController
{ 
	public function index() 
	{ 
		$weekArray = array('日', '一', '二', '三', '四', '五', '六');
		// 先定义一个数组
		$weekDay = '星期'.$weekArray[date('w')];
		$this->assign('weekDay',$weekDay);
		// 得到“星期几”代表的数字
		$weekDayNum = date('w');
    	// 赋值给当前时间“星期几”
		$dayOfWeek = $weekDayNum;
		$courseOfStudent = self::getStudentCourse($dayOfWeek);
		$this->assign('courseOfStudent', $courseOfStudent);
		return $this->fetch();
	}

	/**
	 * @Author    whd
	 * @DateTime  2021-07-02
	 * @param     [int]      $dayOfWeek [传入星期几]
	 * @return    [array]    $courseOfStudent [返回当前星期几的课表]
	 */
	public function getStudentCourse($dayOfWeek)
	{
		//--------------------------------获得课表所用基本参数---------------------------------	
		// 获取当前时间所在的年份
		$nowYear = date('Y');
		// 获取当前所在时间的月份
		$nowMonth = date('m');
		if($nowMonth<= 8)
		{
			// 如果当前月份为上半年，则为一学年下学期，而学年是当前年份数-1（比如：2021年3月为2020-2021学年第二学期）
			$term = 2;
			$yearOfTerm = $nowYear-1;
		}else{
			$term = 1;
			$yearOfTerm = $nowYear;
		}
		// 查询当前时间所在学年学期的学期id
		$nowTerm = Term::where('term_year', $yearOfTerm)->where('term_num', $term)->find();
		$nowTermId = $nowTerm->id;
		// 计算当前周数
		$NowWeek = date('W') - date('W', strtotime($nowTerm->open_date));
		$this->assign('NowWeek', $NowWeek);
		//------------------------------------------------------------------------------------	
		// 从学生表读取所有学生信息
		$student = new Student;
		$dataOfStudent = $student->all();
		// 获得学生总数
		$stuCount = count($dataOfStudent);
		// 从课表读取所有数据
		$studentCourse = new StudentCourse;
		$dataOfStudentCourse = $studentCourse->all();
		// 生成网页显示空课表数组
		for ($count = 0; $count < $stuCount; $count++)
		{
			$studentNameSingle = $dataOfStudent[$count]->student_name;
			$courseOfStudent[$count] = array("$studentNameSingle", '无课', '无课', '无课', '无课', '无课', "\n");
		}
		// 获取全部课程
		$course = new Course;
		$dataOfCourse = $course->all();
		// 所有学生的课程循环读取
		for ($countOfStudent = 0; $countOfStudent < $stuCount; $countOfStudent++)
		{
			$testStudentNum = $countOfStudent;
			// 读取该学号学生全部课表信息
			$singleStudentCourse = $studentCourse->where('student_id',$testStudentNum)->select();
			// 当前单人课表计数
			$countOfSingleStudentCourse = count($singleStudentCourse);
			for ($count = 0; $count < $countOfSingleStudentCourse; $count++)
			{
				if($singleStudentCourse[$count]->Courses->Terms->id === $nowTermId)
				{
					// 判断符合当前时间的课程的上课时间和星期几
					if($singleStudentCourse[$count]->Courses->week_day === (int)$dayOfWeek)
					{		
						$courseOfStudent[$testStudentNum][$singleStudentCourse[$count]->Courses->period+1] = '有课:'.$singleStudentCourse[$count]->Courses->course_name;
					}
				}
			}
		}
		return $courseOfStudent;
	}

	/**
	 * @Author    whd
	 * @DateTime  2021-07-02
	 * 传入星期几的数，得到输入数对应的课表内容
	 */
	public function dayStudentCourse()
	{
		$dayOfWeek = Request::instance()->param('dayOfWeek/d');
		$weekArray = array('日', '一', '二', '三', '四', '五', '六');
		// 先定义一个数组
		$weekDay = '星期'.$weekArray[$dayOfWeek];
		$this->assign('weekDay',$weekDay);
		// 通过页面传会的星期几数字得到课表
		$courseOfStudent = self::getStudentCourse($dayOfWeek);
		$this->assign('courseOfStudent', $courseOfStudent);
		return $this->fetch();
	}

	/**
	 * @Author    whd
	 * @DateTime  2021-07-03
	 * 配置文件首页
	 */
	public function deploy()
	{
		$dingding = new Dingding;
		$dataOfDingding = $dingding::all();
		$this->assign('dataOfDingding', $dataOfDingding);
		return $this->fetch();
	}

	/**
	 * @Author    whd
	 * @DateTime  2021-07-04
	 * 启用钉钉中的一个配置
	 */
	public function activateOn()
	{
		// // 新增钉钉对象
		// $dingding = new Dingding;
		// // 将其他配置都设为未启用
		// $dingdings = $dingding->all();
		// $dingdingCount = count($dingdings);
		// for ($count = 0; $count < $dingdingCount; $count++)
		// {
		// 	if($dingdings[$count]->is_use === 1)
		// 	{
		// 		$test = $dingding->where('id', $dingdings[$count]->id)->update(['is_use' => 0]);
		// 		if (is_null($test)){
		// 			return $this->error('课程信息更新发生错误：' . $dingdings[$count]->getError());
		// 		}
		// 	}
		// }
		// 获取要启用的钉钉配置ID
		$id = Request::instance()->param('id/d');
		// 获取传入的学生信息
		$ding = Dingding::get($id);
		if (is_null($ding)) {
			return $this->error('系统未找到ID为' . $id . '的钉钉配置记录');
		}
		// 数据更新,启用配置的情况下是将is_use变为1
		$ding->is_use = 1;
		if(is_null($ding->save()))
		{
			return $this->error('启用配置发生错误：' . $ding->getError());
		}
		return $this->success('启用成功！', url('deploy'));
	}

	/**
	 * @Author    whd
	 * @DateTime  2021-07-04
	 * 停用钉钉中的一个配置
	 */
	public function activateOff()
	{
		// 获取要启用的钉钉配置ID
		$id = Request::instance()->param('id/d');
		// 获取传入的学生信息
		$ding = Dingding::get($id);
		if (is_null($ding)) {
			return $this->error('系统未找到ID为' . $id . '的钉钉配置记录');
		}
		// 数据更新,停用配置的情况下是将is_use变为0
		$ding->is_use = 0;
		if(is_null($ding->save()))
		{
			return $this->error('停用配置发生错误：' . $ding->getError());
		}
		return $this->success('停用成功！', url('deploy'));
	}

	public function add()
	{
		return $this->fetch();
	}

	public function insert()
	{
		$message='';//提示信息
		$postData=Request::instance()->post();

		//实例化Dingding空对象
		$Dingding = new Dingding();
		// 查询条件
		$map['dingding_url'] = $postData['dingding_url'];
		$map['send_time_hour'] = $postData['send_time_hour'];
		$map['send_time_minute'] = $postData['send_time_minute'];
		$test = $Dingding->where($map)->find();
		// 存在地址和时间一样的则错误
		if(is_null($test))
		{
			//为对象赋值
			$Dingding->dingding_url = $postData['dingding_url'];
			$Dingding->keyword = $postData['keyword'];
			$Dingding->ip_url = $postData['ip_url'];
			$Dingding->send_time_hour = $postData['send_time_hour'];
			$Dingding->send_time_minute = $postData['send_time_minute'];
			$Dingding->check_code = $postData['check_code'];
			$Dingding->remark = $postData['remark'];
			// 默认设置未启用
			$Dingding->is_use = 0;
			//新增对象至数据表
			$result = $Dingding->validate(true)->save();

			//反馈结果
			if($result === false)
			{
				dump($Dingding->getError());
			//验证未通过。发生错误
				$message = '新增失败：'.$Dingding->getError();
			}else{
				dump('跳转');
			//提示操作成功，并跳转至教师管理列表
				return $this->success('新增成功!', url('deploy'));
			}
			return $this->error($message);
		}else{
			return $this->error('新增失败，有重复配置!', url('deploy'));
		}
		
	}

	public function delete()
	{
		try{
			//实例化请求类
			$Request=Request::instance();
			// 获取要启用的钉钉配置ID
			$id = Request::instance()->param('id/d');	
			// 删除
			// 获取要删除的对象
			$Dingding = Dingding::get($id);
			// 不存在
			if(is_null($Dingding))
			{
				throw new \Exception('不存在id为'.$id.'的钉钉配置，删除失败',1);
			}
			// 删除对象
			if(!$Dingding->delete())
			{
				return $this->error('删除失败：'.$Dingding->getError());
			}
		//获取ThinkPHP的内置异常时，直接向上抛出，交给ThinkPHP处理
		}catch(\think\Exception\HttpResponseException $e){
			throw $e;
		//获取到正常的异常时，输出异常
		}catch(\Exception $e){
			return $e->getMessage();
		}
		//进行跳转
		return $this->success('删除成功', $Request->header('referer'));

	}

	public function edit()
	{
		try{
			// 获取传入ID
			$id = Request::instance()->param('id/d');

			// 判断是否成功接收
			if(null === $Dingding = Dingding::get($id))
			{
				// 由于在$this->error抛出了异常，所以也可以省略return(不推荐)
				$this->error('系统未找到ID为'.$id.'的记录');
			}

			// 将数据传给V层
			$this->assign('Dingding', $Dingding);

			// 获取封装好的V层内容
			$htmls = $this->fetch();

			// 将封装好的V层内容返回给用户
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
		$id = Request::instance()->post('id/d');
		// 获取传入的学生信息
		$Dingding = Dingding::get($id);
		if (is_null($Dingding)) {
			return $this->error('系统未找到ID为' . $id . '的记录');
		}
		// 数据更新
		//为对象赋值
		$postData = Request::instance()->post();
		$Dingding->dingding_url = $postData['dingding_url'];
		$Dingding->keyword = $postData['keyword'];
		$Dingding->ip_url = $postData['ip_url'];
		$Dingding->send_time_hour = $postData['send_time_hour'];
		$Dingding->send_time_minute = $postData['send_time_minute'];
		$Dingding->check_code = $postData['check_code'];
		$Dingding->remark = $postData['remark'];

		if (!$Dingding->validate(true)->save()) { 
			return $this->error('更新错误：'.$Dingding->getError());
		} else {
			return $this->success('操作成功', url('deploy'));
		}
	}

	/**
	 * @Author    whd
	 * @DateTime  2021-07-05
	 * 推送钉钉消息
	 */
	public function push()
	{
		$Dingding = new Dingding;
		// 获得钉钉全部对象
		$dingdings = $Dingding->where('is_use', 1)->select();
		//dump($dingdings);
		// 获得钉钉数量
		$dingCount = count($dingdings);
		// 获得当前小时
		$nowHour = date('H');
		// 获得当前分钟
		$nowMinute = date('i');
		// 获取当前秒
		$nowSecond = date('s');
		for ($i = 0; $i<$dingCount; $i++)
		{
			if (($dingdings[$i]->send_time_hour === (int)$nowHour)&&($dingdings[$i]->send_time_minute === (int)$nowMinute)&&((int)$nowSecond === 0))
			{
				$webhook = $dingdings[$i]->dingding_url;
				$testMessage = '学生课表 测试:';
				// 今日课表推送
				// 先定义一个数组
				$weekArray = array('日', '一', '二', '三', '四', '五', '六');
				$weekDay = '星期'.$weekArray[date('w')];
				$this->assign('weekDay',$weekDay);
				// 得到“星期几”代表的数字
				$weekDayNum = date('w');
    			// 赋值给当前时间“星期几”
				$dayOfWeek = $weekDayNum;
				$courseOfStudent = self::getStudentCourse($dayOfWeek);
				// 初始化课表表头
				$dingDingCourseTh[0] = "\n".'姓   名';
				$dingDingCourseTh[1] = '一节';
				$dingDingCourseTh[2] = '二节';
				$dingDingCourseTh[3] = '三节';
				$dingDingCourseTh[4] = '四节';
				$dingDingCourseTh[5] = '五节';
				$dingDingCourseTh[6] = "\n";
				$dingDingCourseTd = null;
				// 表头数组转换成字符串
        		$dingDingCourseThAll = implode('  ',$dingDingCourseTh);

        		$dingDingCourse = $dingDingCourseThAll;
				$dingStuCount = count($courseOfStudent);
				for ($j = 0; $j<$dingStuCount; $j++)
				{	
					// 判断名字长度，如果为两个字则加入空格保持对齐
					$strL = strlen( $courseOfStudent[$j][0]);
					if($strL === 6)
					{
						$courseOfStudent[$j][0] = $courseOfStudent[$j][0].'   ';
					}
					// 每个学生的课表信息转换成字符串
					$dingCourseTd = implode('  ',$courseOfStudent[$j]);
					$dingDingCourse =  $dingDingCourse. $dingCourseTd;
				}
				// 发送钉钉消息
				$this->dingdingSend($testMessage .$dingDingCourse, $webhook);
			}
		}
		return $this->fetch();
	}

	/**
	 * @Author    whd
	 * @DateTime  2021-07-05
	 * 传送字段
	 */
	public function dingdingSend($message ='', $webhook)
	{
		$DingdingSdk = new DingdingSdk;
		$data = array('msgtype' => 'text','text' => array ('content' => $message));
		$DingdingSdk->send($webhook,$data);
	}
}