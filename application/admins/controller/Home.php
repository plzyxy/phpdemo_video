<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/4
 * Time: 17:26
 */

namespace app\admins\controller;


use think\Controller;

class Home extends BaseAdmin
{

    public function index()
    {

        $menus = false;
        $role = $this->db->table('admin_groups')->where(array('gid' => $this->_admin['gid']))->item();
        if ($role) {
            $role['rights'] = (isset($role['rights']) && $role['rights']) ? json_decode($role['rights'], true) : [];
        }
        if ($role['rights']) {
            $where = 'mid in( ' . implode(',', $role['rights']) . ') and ishiden=0 and status=0';
            $menus = $this->db->table('admin_menus')->where($where)->cates('mid');
            $menus && $menus = $this->gettreeitems($menus);
//            dump($menus);
        }
        $site=$this->db->table('sites')->where(array('names'=>"site"))->item();
        $site &&$site['values']=json_decode($site['values']);


        $this->assign('site', $site);
        $this->assign('menus', $menus);
        $this->assign('role', $role);
        return $this->fetch();
    }


    public function welcome()
    {
        return $this->fetch();
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
}