<?php
namespace app\common\model;
use think\Model;    //  导入think\Model类
/**
 * Admin 管理员表
 */
class Admin extends Model
{
    static public function login($username, $password) 
    { 
        // 验证用户是否存在 
        $xinde = array('username' => $username); 
        $Admin = self::get($xinde); 
        //dump($Admin);
        //$type  = $Admin->getData('type');
        //dump($type);
        if (!is_null($Admin)) {
        // 验证密码是否正确 
            if ($Admin->checkPassword($password)) { 
            // 登录 
                session('adminId', $Admin->getData('id')); 
                return true; 
            } 
        }
        return false;
    }
    public function checkPassword($password) 
    { 
        if ($this->getData('password') === $this::encryptPassword($password)){ 
            return true; 
        } else {
           return false; 
       }}

       static public function encryptPassword($password) 
       { 
        //实际的过程中，我还还可以借助其它字符串算法，来实现不同的加密。 
        return sha1(md5($password) . 'mengyunzhi'); 
    }
    /*static  public function encryptPassword($password)
    {
        if (!is_string($password)){
            throw new \RuntimeException("传入变量类属性非字符串，错误码2",2);
        }
        //实际的过程中，我们还可以借助其它字符串算法，来实现不同的加密。
        return sha1(md5($password) . 'mengyunzhi');
    } */
    static public function logOut() 
    {  
        session('adminId', null); 
        return true; 
    }
    static public function isLogin() 
    { 
        $adminId = session('adminId'); 
        // isset()和is_null()是一对反义词 
        if (isset($adminId)) { 
            return true;
        } else { 
            return false; 
        } 
    }
}
