<?php
namespace app\admin\controller;
//use app\lib\database\Login;
use app\admin\model\Admin;
use app\lib\validate\LoginValidate;
use app\lib\exception\ParamerException;
use app\lib\exception\AddressNotExist;
use app\lib\exception\AdminNotExist;
use think\Session;
use app\lib\validate\EmailValidate;
use app\lib\dialogue\EmailNotExist;
use app\lib\email\Email;
use app\lib\validate\ForgetPassword;
class Login extends BaseController{
   
    protected $beforeActionList =[

    ];
    public function login(){     
      if(empty($_POST['login']))
      {
        throw new AddressNotExist();
        die;
      }
      $loginValidate=new LoginValidate();
      $param=$loginValidate->checkData();
      $admin=$param['adminName'];
      $password=md5($param['password']);
      $admin_model=new Admin();
      $user=$admin_model->where('username',$admin)->where('password',$password)->find();
      if(empty($user))
      {
         (new AdminNotExist())->show();
         $userNot->show();
      }
      $user=$user->toArray();
      Session::set('id',$user['id']);
      return $this->redirect('index\login');
    }
    public function forget(){
      if(empty($_POST['login']))
      {
        throw new AddressNotExist();
        die;
      
      }
      $forget=new ForgetPassword();
      $param=$forget->goCheck();
      $email=$param['email'];
      $password=md5($param['password']);
      $user=new Admin();
      $user=$user->where('email',$email)->update(['password'=>$password]);  
      throw new AddressNotExist(['msg' => ' 新密码更新成功 ']);
    }
    public function forgetEmail(){
        if(empty($_POST['login']))
        {
          throw new AddressNotExist();
          die;
        }
        $emailValidate=new EmailValidate();
        $param=$emailValidate->goCheck();
        $model=new Admin();
        $email=$param['email'];
        $user=$model->where('email',$email)->find();
        if(empty($user))
        {
            $e=new EmailNotExist();
            $e->show();
        }
        $send_email=new Email($email);
        $send_email->forget_password();
    }
}