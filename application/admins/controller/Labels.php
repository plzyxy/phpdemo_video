<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/13
 * Time: 18:18
 */

namespace app\admins\controller;

/**
 * Class Lables
 * @package app\admins\controller
 * 标签管理
 */
class Labels extends BaseAdmin
{
    public function channel()
    {

        $channel = $this->db->table('video_label')->where(array('flag' => 'channel'))->lists();
        $this->assign('data', $channel);
        return $this->fetch();
    }

    public function charge()
    {

          $charge = $this->db->table('video_label')->where(array('flag' => 'charge'))->lists();
        $this->assign('data', $charge);
        return $this->fetch();
    }

    public function area()
    {
        $area = $this->db->table('video_label')->where(array('flag' => 'area'))->lists();
        $this->assign('data', $area);
        return $this->fetch();
    }

    public function save()
    {

        $flag = input("post.flag");
        $ords = input('post.ords/a');
        $titles = input('post.titles/a');
        $status = input('post.status/a');
        foreach ($ords as $key => $value) {
            $data['flag'] = $flag;
            $data['ord'] = $value;
            $data['title'] = $titles[$key];
            $data['status'] = isset($status[$key]) ? 1 : 0;


            if ($key == 0 && $data['title']) {
                if($this->db->table('video_label')->insert($data)){
//                    exit(json_encode(array('code' => 0, 'msg' => '添加成功')));
//                    dump("添加成功");
                }else{
//                    exit(json_encode(array('code' => 1, 'msg' => '添加失败')));
//                    dump("添加失败");
                }
            }
            if ($key > 0 && $data['title'] == '') {
                //删除
                if($this->db->table('video_label')->where(array('id' => $key))->delete()){
//                    exit(json_encode(array('code' => 0, 'msg' => '删除成功')));
//                    dump("删除成功");
                }else{
//                    exit(json_encode(array('code' => 1, 'msg' => '删除失败')));
//                    dump("删除失败");
                }

            } else {
                //修改
                if($this->db->table('video_label')->where(array('id' => $key))->update($data)){
//                    exit(json_encode(array('code' => 0, 'msg' => '保存成功')));
//                    dump("保存成功");
                }else{
//                    exit(json_encode(array('code' => 1, 'msg' => '保存失败')));
//                    dump("保存失败");
                }

            }

        }
        exit(json_encode(array('code' => 0, 'msg' => '保存成功')));

    }


}