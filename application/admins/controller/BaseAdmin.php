<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/4
 * Time: 17:39
 */

namespace app\admins\controller;


use think\Controller;
use think\Request;
use util\data\Sysdb;

class BaseAdmin extends Controller
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->_admin=session('admin');
        //'没有session  去登录页
        if (!$this->_admin){
            header('location:/index.php/admins/Account/login');
            exit;
        }
        //  权限
        $this->assign('admin',$this->_admin);
        $this -> db = new Sysdb();

    }




}