<?php

namespace app\admins\controller;

use think\Controller;
use util\data\Sysdb;

class Account extends Controller
{

    public function login()
    {
        return $this->fetch();

    }

    public function dologin()
    {
        $username = trim(input('post.username'));
        $pwd = trim(input('post.pwd'));
        $verifycode = trim(input('post.verifycode'));
        if ($username == '') {
            exit(json_encode(array('code' => 1, 'msg' => '用户名不能为空')));
        }
        if ($pwd == '') {
            exit(json_encode(array('code' => 1, 'msg' => '密码不能为空')));
        }
        if ($verifycode == '') {
            exit(json_encode(array('code' => 1, 'msg' => '验证码不能为空')));
        }
//            if (!captcha_check($verifycode)){
//                exit(json_encode(array('code'=>1,'msg'=>'验证码错误啦啦啦')));
//            }
        //判断用户
        $this->db = new Sysdb();

        $admin = $this->db->table('admins')->field('status,gid,password,username')->where(array("id" => 10086))->item();


        if (!$admin) {
            exit(json_encode(array('code' => 1, 'msg' => '用户名不存在')));
        }
        if (md5( $pwd) != $admin['password']) {

            exit(json_encode(array('code' => 1, 'msg' => ' 账号作或密码错误 !')));
        }
        if ($admin['status'] == 1) {
            exit(json_encode(array('code' => 1, 'msg' => '用户禁用')));
        }

        session('admin', $admin);
        exit(json_encode(array('code' => 0, 'msg' => '登录成功啦')));
    }
   public function logout(){
       session('admin', null);
       exit(json_encode(array('code' => 0, 'msg' => '退出成功')));
   }


}