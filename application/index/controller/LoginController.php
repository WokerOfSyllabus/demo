<?php 
namespace app\index\controller; 
use think\Controller; 
use think\Request; // 请求//
use app\common\model\Admin;
class LoginController extends Controller 
{ 
    // 用户登录表单
    public function index()
    {
    // 显示登录表单
        return $this->fetch();
    }
    
    public function login()
    {
        // 接收post信息 
        $postData = Request::instance()->post(); 
        // 直接调用M层方法，进行登录。 
        if (Admin::login($postData['username'], $postData['password'])) { 
            return $this->success('管理员登陆成功', url('Admin/index')); 
        } else { 
            return $this->error('登录用户名或密码不正确', url('index')); 
        }
    }
    // 注销 
    public function logOut() 
    { 
        if (Admin::logOut()) { 
            return $this->success('注销成功', url('index')); 
        } else { 
            return $this->error('注销失败r', url('index')); 
        } 
    }
    public function test() 
    { 
        echo Admin::encryptPassword('123'); 
    }
}