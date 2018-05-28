<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/11
 * Time: 17:17
 */

namespace app\admins\controller;

class Menu extends BaseAdmin
{
    public function index()
    {
        $pid = (int)input("get.pid");
        $data['lists'] = $this->db->table("admin_menus")->where(array('pid' => $pid))->lists();

//上级菜单
        $backid = 0;
        if ($pid > 0) {
            $parent = $this->db->table("admin_menus")->where(array('mid' => $pid))->item();
            $backid = $parent['pid'];
        }

        $this->assign('pid', $pid);
        $this->assign('backid', $backid);
        $this->assign('data', $data);
        return $this->fetch();
    }

//增加新的菜单
    public function save()
    {
        $pid = (int)input("post.pid");
        $ords = input('post.ord/a');
        $titles = input('post.title/a');
        $controllers = input('post.controller/a');
        $methods = input('post.method/a');
        $ishidens = input('post.ishiden/a');
        $status = input('post.status/a');

        foreach ($ords as $key => $value) {
            $data['pid'] = $pid;
            $data['ord'] = $value;
            $data['title'] = $titles[$key];
            $data['controller'] = $controllers[$key];
            $data['method'] = $methods[$key];
            $data['ishiden'] = isset($ishidens[$key]) ? 1 : 0;
            $data['status'] = isset($status[$key]) ? 1 : 0;

//      dump($data);

            if ($key == 0 && $data['title']) {
                $this->db->table('admin_menus')->insert($data);
            }
            if ($key > 0  && $data['title']==''&& $data['controller'] == '' && $data['method'] == '') {
                //删除
                $this->db->table('admin_menus')->where(array('mid' => $key))->delete();
            } else {
                //修改
                $this->db->table('admin_menus')->where(array('mid' => $key))->update($data);

            }

        }
        exit(json_encode(array('code' => 0, 'msg' => '保存成功')));


    }


}