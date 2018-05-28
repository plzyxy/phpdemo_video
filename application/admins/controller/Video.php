<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/16
 * Time: 14:05
 */

namespace app\admins\controller;


use think\Db;

class Video extends BaseAdmin
{

    function index()
    {
        $data['pageSize'] = 10;
        $data['page'] = max(1, (int)input('get.page'));
        $where = array();
        $data['wd'] = trim(input('get.wd'));
        $data['wd'] && $where = 'title like "%' . $data['wd'] . '%"';
        $data['data'] = $this->db->table('video')->where($where)->order('id desc')->pages($data['pageSize']);
        $label_ids = [];
        foreach ($data['data']['lists'] as $item) {
            !in_array($item['channel_id'], $label_ids) && $label_ids[] = $item['channel_id'];
            !in_array($item['charge_id'], $label_ids) && $label_ids[] = $item['charge_id'];
            !in_array($item['area_id'], $label_ids) && $label_ids[] = $item['area_id'];
        }
        $label_ids && $data['labels'] = $this->db->table('video_label')->where('id in(' . implode(',', $label_ids) . ')')->cates('id');


        $this->assign('data', $data);
        return $this->fetch();
    }

    //add
    public function add()
    {

        $data['channel'] = $this->db->table('video_label')->where(array('flag' => 'channel'))->lists();
        $data['charge'] = $this->db->table('video_label')->where(array('flag' => 'charge'))->lists();
        $data['area'] = $this->db->table('video_label')->where(array('flag' => 'area'))->lists();
        $id = (int)input('get.id');
        $data['item'] = $this->db->table('video')->where(array('id' => $id))->item();

        $this->assign('data', $data);
        return $this->fetch();
    }

    //add
    public function upload_img()
    {

        $file = $this->request->file('file');
        if ($file == null) {
            exit(json_encode(array('code' => 1, 'msg' => '文件不存在')));
        }
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
        $ext = ($info->getExtension());
        if (!in_array($ext, array('jpg', 'jpeg', 'gif', 'png'))) {
            exit(json_encode(array('code' => 1, 'msg' => '文件格式不支持')));
        }
        $img = '/uploads/' . $info->getSaveName();
        exit(json_encode(array('code' => 0, 'msg' => $img)));


    }

    public function save()
    {
        $id = (int)input('post.id');
        $data["title"] = trim(input('post.title'));
        $data["channel_id"] = trim(input('post.channel_id'));
        $data["charge_id"] = trim(input('post.charge_id'));
        $data["area_id"] = trim(input('post.area_id'));
        $data["url"] = trim(input('post.url'));
        $data["img"] = trim(input('post.img'));
        $data["keywords"] = trim(input('post.keywords'));
        $data["desc"] = trim(input('post.desc'));
        $data["status"] = trim(input('post.status'));
        if ($data["title"] == null) {
            exit(json_encode(array('code' => 1, 'msg' => '影片名称不能为空')));
        }
        if ($data["url"] == null) {
            exit(json_encode(array('code' => 1, 'msg' => '影片链接不能为空')));
        }
        if ($id > 0) {
            $this->db->table('video')->where(array('id' => $id))->update($data);
        } else {
            $this->db->table('video')->insert($data);
        }

        exit(json_encode(array('code' => 0, 'msg' => '保存成功')));
    }

    /**
     * 删除video
     */
    public function delete()
    {

        $id = (int)input('post.id');
        if ($this->db->table('video')->where(array('id' => $id))->delete()) {

            exit(json_encode(array('code' => 0, 'msg' => '删除成功')));
        } else {
            exit(json_encode(array('code' => 1, 'msg' => '删除失败')));

        }

    }


}


