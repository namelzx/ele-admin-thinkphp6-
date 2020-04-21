<?php
/**
 * Created by PhpStorm.
 * User: lzx
 * Date: 2020-04-15
 * Time: 06:28
 */

namespace app\admin\model;


use think\Model;

class AuthGroupModel extends Model
{
    protected $table = 'tp_auth_group';

    /** 通过ID获取分组信息
     * @param $groupId 用户组id
     */
    public function getGroupById($groupId)
    {
        $where = ['id' => $groupId];
        return $this->where($where)->find();
    }

    /** 获取分组列表
     * @param $title
     * @param $status
     */
    public function getLists($title, $status = 0)
    {
        $where = [];
        if ($title) {
            $where[] = ['title', 'like', '%' . $title . '%'];
//            $where .= " and title like '%" . $title . "%' ";
        }
//        if ($status != -1) {
//            $where .= " and status = " . $status;
//        }

        return $this->where($where)->select();
    }

    /**
     * 获取所有的列表,不分页
     */
    public function getListsAll($status, $myorder)
    {
        $where = true;
        if ($status != -1) {
            $where .= " and a.status =  " . $status;
        }
        return $this->alias('a')->field('a.*')->where($where)->order($myorder)->select();
    }

    /** 获取分组数量
     * @param $title
     * @param $status
     */
    public function getTotal($title, $status)
    {
        $where = true;
        if ($title) {
            $where .= " and title like '%" . $title . "%' ";
        }
        if ($status != -1) {
            $where .= " and status = " . $status;
        }
        return $this->where($where)->count();
    }

    /** 更新
     * @param array $id
     * @param array $data
     * @return $this|void
     */
    public function modify($id, $data)
    {
        return $this->where(['id' => $id])->update($data);
    }

    /**新增
     * @param $data
     */
    public function add($data)
    {
        return $this->insertGetId($data);
    }

    /** 删除
     * @param $id
     * @return int
     */
    public function del($id)
    {
        return $this->where('id', $id)->delete();
    }

    /** 批量删除
     * @param $ids
     * @return int
     */
    public function delall($ids)
    {
        return $this->where('id', 'in', $ids)->delete();
    }

    /**
     * [check 检测是否存在]
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public function check($name)
    {
        $id = $this->where('title', $name)->value('id');
        if (empty($id)) {
            return 0;
        } else {
            return $id;
        }
    }

}