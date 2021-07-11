<?php
namespace app\index\controller;//命名空间，也说明了文所在的文件夹
use think\Controller;
use think\Request;
use app\common\model\Teacher;//引入教师
use app\common\model\Term;//引入学期
use app\common\model\Student;//引入学生
use app\common\model\StudentCourse;//引入学生课表

/**
*IndexController既是类名，也是文件名，说明这个文件的名字为Index.php。
* 由于其子类需要使用think\Controller中的函数，所以在此必须进行继承,并在构造函数中，进行父类构造函数的初始化
*/
class IndexController extends Controller
{
	public function __construct()
	{
		//调用父类构造函数（必须）
		parent::__construct();
	}

	public function index()
	{
		$terms = Term::all();
		$this->assign('terms',$terms);
		// 获取当前时间所在的年份
		$nowYear = date('Y');
		// 获取当前所在时间的月份
		$nowMonth = date('m');
		// 获取当前天
		$nowDay = date('d');
		$nowTime = $nowYear.'-'.$nowMonth.'-'.$nowDay;
		// 当前时间转换成数值方便计算
		$nowTimeNum = strtotime($nowTime);
		$countTerm = count($terms);
		for ($i=0; $i < $countTerm; $i++) { 
			if (($nowTimeNum >= strtotime($terms[$i]->start_time))&&($nowTimeNum <= strtotime($terms[$i]->end_time)))
			{
				// 将当前学期传给V层
				$nowTerm = $terms[$i];
				$this->assign('nowTerm',$nowTerm);
				$this->assign('termId',$nowTerm-> id);
				// 将当前周数范围传给V层
				// 计算总周数
				$countWeek = date('W', strtotime($terms[$i]->end_time)) - date('W', strtotime($terms[$i]->start_time));
				// 赋值
				for ($j=1; $j <= $countWeek; $j++) { 
					$termWeekInfo[$j] = array('week' => $j);
				}
				$this->assign('termWeekInfo', $termWeekInfo);
				// 计算当前周次
				$nowWeek = date('W') - date('W', strtotime($terms[$i]->start_time));
				$this->assign('selectedWeek',$nowWeek);
			}

		}
		$nowWeekDay = $nowDay;
		self::getStudentCourseAllWeek( $nowTerm->id, $nowWeek);
		return $this->fetch();
	}

	public function CourseInfo()
	{
		// 获取当前时间所在的年份
		$nowYear = date('Y');
		// 获取当前所在时间的月份
		$nowMonth = date('m');
		// 获取当前天
		$nowDay = date('d');
		$nowTime = $nowYear.'-'.$nowMonth.'-'.$nowDay;
		// 当前时间转换成数值方便计算
		$nowTimeNum = strtotime($nowTime);
		$termId = Request::instance()->param('term_id/d');
		$weekNum = Request::instance()->param('week/d');
		$terms = Term::all();
		$this->assign('terms',$terms);
		if($weekNum === null)
		{
			// 将查询学期传给V层
			$nowTerm = Term::where('id', $termId)->find();
			$this->assign('nowTerm',$nowTerm);
			$this->assign('termId',$nowTerm-> id);			
			// 计算总周数
			$date = self::format($nowTerm->end_time, $nowTerm->start_time);
			// 赋值
			for ($j=1; $j <= $date['weekAll']; $j++) { 
				$termWeekInfo[$j] = array('week'=> $j);
			}
			if (($nowTimeNum >= strtotime($nowTerm->start_time))&&($nowTimeNum <= strtotime($nowTerm->end_time)))
			{
				// 计算当前周次
				$week = date('W') - date('W', strtotime($nowTerm->start_time));
				$this->assign('selectedWeek', $week);
			}else{
				// 默认周次为1
				$week = 1;
				$this->assign('selectedWeek', 1);
			}
			$this->assign('termWeekInfo',$termWeekInfo);
			self::getStudentCourseAllWeek( $nowTerm->id, $week);
		}else{	
			// 将查询学期传给V层
			$nowTerm = Term::where('id', $termId)->find();
			$this->assign('nowTerm',$nowTerm);
			$this->assign('termId',$nowTerm-> id);
			// 计算总周数
			$date = self::format($nowTerm->end_time, $nowTerm->start_time);
			// 赋值
			for ($j=1; $j <= $date['weekAll']; $j++) { 
				$termWeekInfo[$j] = array('week' => $j);
			}
			$this->assign('selectedWeek', $weekNum);
			$this->assign('termWeekInfo', $termWeekInfo);
			self::getStudentCourseAllWeek($termId, $weekNum);
		}
		return $this->fetch();
	}

	public function getStudentCourseAllWeek($termId, $week)
	{
		$studentCourseDay0 = self::getStudentCourse( $termId, $week, 0);
		$this->assign('studentCourseDay0', $studentCourseDay0);
		$studentCourseDay1 = self::getStudentCourse( $termId, $week, 1);
		$this->assign('studentCourseDay1', $studentCourseDay1);
		$studentCourseDay2 = self::getStudentCourse( $termId, $week, 2);
		$this->assign('studentCourseDay2', $studentCourseDay2);
		$studentCourseDay3 = self::getStudentCourse( $termId, $week, 3);
		$this->assign('studentCourseDay3', $studentCourseDay3);
		$studentCourseDay4 = self::getStudentCourse( $termId, $week, 4);
		$this->assign('studentCourseDay4', $studentCourseDay4);
		$studentCourseDay5 = self::getStudentCourse( $termId, $week, 5);
		$this->assign('studentCourseDay5', $studentCourseDay5);
		$studentCourseDay6 = self::getStudentCourse( $termId, $week, 6);
		$this->assign('studentCourseDay6', $studentCourseDay6);
	}

    public function getStudentCourse($termId, $week,$weekDay)
    {
    	$Student = new Student;
    	$students = $Student::all();
		// 获得学生总数
    	$countStu = count($students);
		// 生成网页显示空课表数组
    	for ($count = 0; $count < $countStu; $count++)
    	{
    		$stuName = $students[$count]->name;
    		$studentCourse[$count] = array("$stuName", '无课', '无课', '无课', '无课', '无课', "\n");
    	}
		// 查询当前课表内所有课程
    	$studentCourseAll = StudentCourse::all();
    	$countStuCourse = count($studentCourseAll);
		// 一个人的课表
    	for ($stuJiShu=0; $stuJiShu < $countStu; $stuJiShu++) { 
    		$studentCourseOne = $students[$stuJiShu]->StudentCourses;

    		for ($i=0; $i < count($studentCourseOne); $i++) { 
    			$stuCourseTermInfo = $studentCourseOne[$i]->Courses->TermCourses;
    			for ($j=0; $j < count($stuCourseTermInfo); $j++) { 
    				if (($stuCourseTermInfo[$j]->week === $week)&&($stuCourseTermInfo[$j]->week_day === $weekDay)&&($stuCourseTermInfo[$j]->term_id === $termId))
    				{
    					$studentCourse[$stuJiShu][$stuCourseTermInfo[$j]->period] = '有课';
    				}
    			}
    		}
    	}
    	return $studentCourse;
    }

    public function format($a,$b)
	{
        //检查两个日期大小，默认前小后大，如果前大后小则交换位置以保证前小后大
		if(strtotime($a)>strtotime($b)) list($a,$b)=array($b,$a);
		$start  = strtotime($a);
		$stop   = strtotime($b);
		$extend = ($stop-$start)/86400;
		$result['extends'] = $extend;
        if($extend<7){                //如果小于7天直接返回天数
        	$result['daily'] = $extend;
        }elseif($extend<=31){        //小于28天则返回周数，由于闰年2月满足了
        	if($stop==strtotime($a.'+1 month')){
        		$result['monthly'] = 1;
        	}else{
        		$w = floor($extend/7);
        		$d = ($stop-strtotime($a.'+'.$w.' week'))/86400;
        		$result['weekly']  = $w;
        		$result['daily']   = $d;
        	}
        }else{
        	$y=    floor($extend/365);
            if($y>=1){                //如果超过一年
            	$start = strtotime($a.'+'.$y.'year');
            	$a     = date('Y-m-d',$start);
                //判断是否真的已经有了一年了，如果没有的话就开减
            	if($start>$stop){
            		$a = date('Y-m-d',strtotime($a.'-1 month'));
            		$m =11;
            		$y--;   
            	}
            	$extend = ($stop-strtotime($a))/86400;
            }
            if(isset($m)){
            	$w = floor($extend/7);
            	$d = $extend-$w*7;
            }else{
            	$m = isset($m)?$m:round($extend/30);
            	$stop>=strtotime($a.'+'.$m.'month')?$m:$m--;
            	if($stop>=strtotime($a.'+'.$m.'month')){
            		$d=$w=($stop-strtotime($a.'+'.$m.'month'))/86400;
            		$w = floor($w/7);
            		$d = $d-$w*7;
            	}
            }
            $result['yearly']  = $y;
            $result['monthly'] = $m;
            $result['weekly']  = $w;
            $result['daily']   = isset($d)?$d:null;
            $result['weekAll']   = floor($extend/7);
        }
        return array_filter($result);
    }
}
