<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/12
 * Time: 11:46
 */

namespace app\admins\controller;


class Roles extends BaseAdmin
{

    public function index()
    {

        $data['roles'] = $this->db->table('admin_groups')->lists();
        $this->assign('data', $data);
//        dump($data);
        return $this->fetch();

    }

    public function add()
    {
        $gid = (int)input("get.gid");
//        dump($gid);
        $roles = $this->db->table('admin_groups')->where(array('gid' => $gid))->item();
        $roles && $roles['rights'] && $roles['rights'] = json_decode($roles['rights']);
//        dump($roles);
        //是否管理员数据
        $menu_list = $this->db->table("admin_menus")->where(array('status' => 0))->cates('mid');
        $menus = $this->gettreeitems($menu_list);
        $results = array();
        foreach ($menus as $value) {
            $_data = isset($value['children']) ? $value['children'] : $value;
            $value['children'] = isset($value['children']) ? $this->formatMenus($_data) : false;
            $results[] = $value;
        }


//        //角色
//        $data['groups'] = $this->db->table('admin_groups')->field("*")->cates('gid');

        $this->assign('roles', $roles);
        $this->assign('menus', $results);
        return $this->fetch();
    }

    //保存新的角色
    public function save()
    {

        $gid = (int)input('post.gid');
        $data['title'] = trim(input('post.title'));
        $menus = input('post.menu/a');
        if (!$data['title']) {
            exit(json_encode(array('code' => 1, 'msg' => '角色名不能为空')));
        }
        $menus && $data['rights'] = json_encode(array_keys($menus));
        if ($gid) {
           if( $this->db->table('admin_groups')->where(array('gid' => $gid))->update($data)) {
               exit(json_encode(array('code' => 0, 'msg' => '保存成功')));
           }else{
               exit(json_encode(array('code' => 0, 'msg' => '保存失败')));
           }

        } else {
            if($this->db->table('admin_groups')->insert($data)){
                exit(json_encode(array('code' => 0, 'msg' => '保存成功')));
            }else{
                exit(json_encode(array('code' => 0, 'msg' => '保存失败')));

            }
        }

    }

    public function deletes(){
        $gid = (int)input('post.gid');
       if( $this->db->table('admin_groups')->where(array('gid' => $gid))->delete()){

           exit(json_encode(array('code' => 0, 'msg' => '删除成功')));
       }else{
           exit(json_encode(array('code' => 0, 'msg' => '删除失败')));

       }
    }



    private function gettreeitems($items)
    {
        $tree = array();
        //用指针
        foreach ($items as $item) {
            if (isset($items[$item['pid']])) {
                $items[$item['pid']]['children'][] =& $items[$item['mid']];
            } else {
                $tree[] =& $items[$item['mid']];
            }
        }
        return $tree;
    }

    private function formatMenus($items, &$res = array())
    {
        foreach ($items as $item) {
            if (!isset($item['children'])) {
                $res[] = $item;
            } else {
                $tem = $item['children'];
                unset($item['children']);
                $res[] = $item;
                $this->formatMenus($tem, $res);//递归
            }
        }
        return $res;
    }


}