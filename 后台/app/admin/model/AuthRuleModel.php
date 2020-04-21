<?php
/**
 * Created by PhpStorm.
 * User: lzx
 * Date: 2020-04-16
 * Time: 13:10
 */

namespace app\admin\model;


use think\Model;

class AuthRuleModel extends Model
{
    protected $table = 'tp_auth_rule';

    /** 通过ID获取权限信息
     * @param $ruleId 权限id
     */
    public function getRuleById($ruleId)
    {
        $where = ['id' => $ruleId];
        return $this->where($where)->find();
    }

    /** 通过PID获取权限信息
     * @param $pid 权限pid
     */
    public function getRuleByPid($pid)
    {
        $where = ['pid' => $pid];
        return $this->where($where)->find();
    }

    /** 权限id 数组
     * @param $ruleIds
     * @return
     */
    public function getRuleByIds($ruleIds)
    {

        $where = [];
        $where[] = ['id', 'in', $ruleIds];
        $where[] = ['status', '=', 1];
        return $this->where($where)->order('sorts', ' asc')->select();
    }

    /** 根据名称获取权限
     * @param $name
     * @return
     */
    public function getRuleByName($name)
    {
        return $this->where(['name' => $name])->find();
    }

    /**
     * 获取列表
     */
    public function getLists($title, $status, $myorder)
    {
        $where = true;
        if ($title) {
            $where .= " and a.title like '%" . $title . "%' ";
        }

        if ($status != -1) {
            $where .= " and a.status =  " . $status;
        }
        return $this->alias('a')->field('a.*')->where($where)->order($myorder)->select();
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

    /**
     * 获取数量
     */
    public function getTotal($title, $status)
    {
        $where = true;
        if ($title) {
            $where .= " and a.title like '%" . $title . "%' ";
        }

        if ($status != -1) {
            $where .= " and a.status =  " . $status;
        }
        return $this->alias('a')->where($where)->count();
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

    /**
     * [check 检测是否存在]
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public function check($name)
    {
        $id = $this->where('name', $name)->value('id');
        if (empty($id)) {
            return 0;
        } else {
            return $id;
        }
    }

    //排序
    public function sort($id, $sorts)
    {
        return $this->where('id', $id)->update(['sorts' => $sorts]);
    }

}