<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/10
 * Time: 10:42
 */
namespace app\admins\controller;

ini_set('display_errors',1);            //错误信息
ini_set('display_startup_errors',1);    //php启动错误信息
error_reporting(E_ERROR | E_WARNING | E_PARSE);


/**
 * Class Admin  管理员列表
 * @package app\admins\controller
 */
class Admin extends BaseAdmin
{

    public function index()
    {

        $data['lists'] = $this->db->table('admins')->lists();
        $data['groups'] = $this->db->table('admin_groups')->field("*")->cates('gid');
        $this->assign('data', $data);
        return $this->fetch();
    }

//增加
    public function add()
    {
        $id=(int)input("get.id");
        //是否管理员数据
        $data['item']=$this->db->table("admins")->where(array('id'=>$id))->item();
        //角色
        $data['groups'] = $this->db->table('admin_groups')->field("*")->cates('gid');
        $this->assign('data', $data);
        return $this->fetch();
    }

    //delete
    public function delete(){
         $id=(int)input('post.id');
         $res=$this->db->table('admins')->where(array('id'=>$id))->delete();
         if (!$res){
             exit(json_encode(array('code' => 1, 'msg' => '删除失败')));
         }else{
             exit(json_encode(array('code' => 0, 'msg' => '删除成功')));
         }

    }



    //save
    public function save()
    {
        $data['username'] = trim(input('post.username'));
        $id = (int)trim(input('post.id'));
        $data['gid'] = (int)input('post.gid');
       $pwd = trim(input('post.pwd'));
        $data['truename'] = trim(input('post.truename'));
        $data['status'] = (int)(input('post.status'));

        if (!$data['username']) {
            exit(json_encode(array('code' => 1, 'msg' => '用户名不能为空')));
        }
        if (!$data['gid']) {
            exit(json_encode(array('code' => 1, 'msg' => '角色不能为空')));
        }
        if (!$data['truename']) {
            exit(json_encode(array('code' => 1, 'msg' => '真实名不能为空')));
        }
        $res=true;
        if(!$id==0){
             $this->db->table('admins')->where(array('id'=>$id))->update($data);
        }else{
            if (!$pwd) {
                exit(json_encode(array('code' => 1, 'msg' => '密码不能为空')));
            }
            $item = $this->db->table('admins')->where(array("username" => $data['username']))->item();
            if ($item) {
                exit(json_encode(array('code' => 1, 'msg' => '用户名已存在')));
            }
//            $data['add_time'] = time();
            $data['password']=md5($pwd);
            $res = $this->db->table('admins')->insert($data);
        }




//        dump($data);
//        $item = $this->db -> table('admins')->where(array('username' => $data['username']))->item();

//

        if (!$res) {
            exit(json_encode(array('code' => 1, 'msg' => '保存失败')));
        }
        exit(json_encode(array('code' => 0, 'msg' => '保存成功')));


    }

}