<?php
namespace app\index\controller;
use think\Controller;
use app\common\model\Admin;
use app\common\model\Student;
use think\Request;
use think\Validate;   
class AdminController extends IndexController
{   
    //index索引
    public function index()
    {

        $username = input('get.username');
        $pageSize = 2; // 每页显示5条数据  
        $Admin = new Admin; 
        // 打印$Admin 至控制台 
        trace($Admin, 'debug');
        // 按条件查询数据并调用分页
        $admins = $Admin->where('username', 'like', '%' . $username . '%')->paginate($pageSize, false, [
         'query'=>[
          'username' => $username,
      ],
  ]);
        //向V层传数据 
        $this->assign('admins', $admins); 
        // 取回打包后的数据 
        $htmls = $this->fetch(); 
        // 将数据返回给用户 
        return $htmls;
    }
    public function insert ()
    {
        $postData = Request::instance()->post();
        //$postData = $this->request->post();
        $Admin = new Admin();
        $Admin->username =  $postData['username'];
        $Admin->password =  $postData['password'];
        //$Admin->save();
        if (false === $Admin->validate(true)->save($postData)) { 
            return  $this->error('更新失败：' . $Admin->getError(),$_POST['httpref']);
            //$message = '更新失败：' . $Admin->getError(); 
        } else {
            return  $this->success('编辑成功',$_POST['httpref']);
        }
        //return $this->success( $Admin->username . '新增成功。新增用户名为：' . $Admin->id,url('index'));
    }
    public function add() 
    { 
        $this->assign('Admin', new Admin); 
        return $this->fetch();
    } 
    public function delete()
    {
        $id = Request::instance()->param('id/d');
        if (is_null($id) || 0 ===$id){
            return $this->error('未获取到id信息',url('index'));
        }
        $Admin = Admin::get($id);
        if(is_null($Admin)){
            return $this ->error('不存在id为' . $id . '的用户,删除失败',url('index'));
        }
        if(!$Admin->delete()){
            return $this->error('删除失败' . $Admin->getError(),url('index'));
        }
        return $this->success('删除成功', url('index'));
        //return  $this->success('编辑成功',$_POST['httpref']);
    }
    public function edit ()
    {
        $id = Request::instance()->param('id/d');
        //dump($id);
        if(is_null($Admin = Admin::get($id))){
            return '系统未找到ID为' . $id . '的记录';
        }
        $this->assign('Admin', $Admin); 
        $htmls =  $this->fetch();
        return $htmls;
    }
    public function update()
    {
        $admin = Request::instance()->post(); 
        $Admin = new Admin(); 
        //dump($admin['httpref']);
        $message = '更新成功'; 
        // 依据状态定制提示信息 
        if (false === $Admin->validate(true)->isUpdate(true)->save($admin)) { 
            return  $this->error($message = '更新失败：' . $Admin->getError(),$_POST['httpref']);
            //$message = '更新失败：' . $Admin->getError(); 
        } else {
            return  $this->success('编辑成功',$_POST['httpref']);
        }
    }  
}
