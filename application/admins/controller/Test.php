<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/2
 * Time: 14:48
 */
namespace app\admins\controller;

use think\Controller;

use Util\data\Sysdb;
class Test extends  Controller {

    public function  index(){
//       phpinfo();exit;
        $this->db=new Sysdb();
        // p($this->db);exit;

//        $ress=$this->db->table('admins')->field('id,username')/*->where(array("username"=>"admin"))*/->lists();
        $ress=$this->db->table('admins')->field('id,username')->lists();
        dump($ress);

        echo '<hr>';
        $res2=$this->db->table('admins')->field("id,username")->cates('id');
        dump($res2);

        echo '<hr>';
        $data['item']=$this->db->table("admins")->where(array('id'=>10087))->item();
        dump( $data['item']);


//        $res=$this->db->table('admins')->field('id,username')->where(array("id"=>10086))->lists();
//        dump($res);
    }







}