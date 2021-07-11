<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use app\common\model\Term;
use think\Request;
use think\Validate;
class TermController extends Controller
{
	public function index ()
	{

		// 获取查询信息
        $name = Request::instance()->get('name');
        // echo $name;

        $pageSize = 5; // 每页显示5条数据

        $Term = new Term;

        // 打印$Course 至控制台
        trace($Term, 'debug');

        // 按条件查询数据并调用分页
        $terms = $Term->where('name', 'like', '%' . $name . '%')->paginate
        ($pageSize, false, [
            'query'=>[
                'name' => $name,
            ],
        ]);

		/*$Term = new Term ;
		$terms =$Term->select();*/
        // 向V层传数据 
		$this->assign('terms', $terms); 
        // 取回打包后的数据 
		$htmls = $this->fetch(); 
        // 将数据返回给用户
		return $htmls;
	}


	 public function save()
    {
        //存课程信息
        $Term = new Term();
       	$Term->name = Request::instance()->post('name');
		$Term->start_time = Request::instance()->post('start_time');
		$Term->end_time = Request::instance()->post('end_time');
        //新增数据并验证
        if (!$Term->save()){
            return $this->error('保存错误：'.$Term->getError());
        }
        
        unset($Term);//unset出现的位置和new语句的缩进量相同，在返回前，最后被执行。

        return $this->success('添加成功', $_POST['httpref']);
        /*return $this->success('操作成功', url('index'));*/
    }

	public function insert ()
	{
		$postData = Request::instance()->post();
        //$postData = $this->request->post();
		dump($postData);
		$Term = new Term();
	    $Term->name = Request::instance()->post('name');
        $Term->start_time = Request::instance()->post('start_time');
        $Term->end_time = Request::instance()->post('end_time');
		$Term->save();
		return $this->success( $Term->name . '新增学期成功。新增ID为：' . $Term->id,url('index'));
	}
	public function add() 
	{ 
		$this->assign('Term', new Term); 
		return $this->fetch();
	} 
	public function edit ()
	{
		$id = Request::instance()->param('id/d');
		if(is_null($Term = Term::get($id))){
			return '系统未找到ID为' . $id . '的记录';
		}
		$this->assign('Term', $Term); 
		$htmls =  $this->fetch();
		return $htmls;
	}
	public function update()
	{
    	/*
        $term = Request::instance()->post();
        $Term = new Term();
        $state = $Term->validate(true)->isUpdate()->save($term);
        if($state){
            //return '更新成功';
            return $this->success('更新成功', url('index'));
        } else {
            return '更新失败';
        }*/

        try {
            // 接收数据，获取要更新的关键字信息 
        	$id = Request::instance()->post('id/d'); 
        	$message = '更新成功'; 
            // 获取当前对象 
        	$Term = Term::get($id); 
        	if (!is_null($Term)) { 
            // 写入要更新的数据 
        		$Term->name = Request::instance()->post('name');
                $Term->start_time = Request::instance()->post('start_time');
                $Term->end_time = Request::instance()->post('end_time');
        		$Term->save();
            // 更新 
        		if (false === $Term->validate(true)->save($Term->getData())) { 
        			$message = '更新失败' . $Term->getError(); 
        		}
        	} else { 
        		throw new \Exception("所更新的记录不存在", 1); 
            // 调用PHP内置类时 ，需要在前面加上 \ 
        	}
        } catch (\Exception $e) { 
        // 由于对异常进行了处理，如果发生了错误，我们仍然需要查看具体的异常位置及信息， 那么需要将以下的代码的注释去掉 
        // throw $e
        	$message = $e->getMessage(); 
        }
        return $this->success('编辑成功', $_POST['httpref']);
        /*return $this->success( $message,url('index'));*/
        
}
public function delete()
{
	$id = Request::instance()->param('id/d');
	if (is_null($id) || 0 ===$id){
		return $this->error('未获取到id信息',url('index'));
	}
	$Term = Term::get($id);
	if(is_null($Term)){
		return $this ->error('不存在id为' . $id . '的学期,删除失败',url('index'));
	}
	if(!$Term->delete()){
		return $this->error('删除失败' . $Term->getError(),url('index'));
	}
	return $this->success('删除成功', url('index'));
}
}