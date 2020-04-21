<?php
/**
 * Created by PhpStorm.
 * User: lzx
 * Date: 2020-04-16
 * Time: 13:10
 */

namespace app\admin\services;


use app\admin\model\AdminModel;
use app\admin\model\AuthGroupModel;
use app\admin\model\AuthRuleModel;
use app\util\TreeUtil;

class AuthRuleService
{


    /** 根据名称获取权限
     * @param $name 权限标识
     * @param $groupId 分组id
     * @return bool
     */
    public function hasAccessByName($name, $groupId)
    {
        $rule = (new AuthRuleModel())->getRuleByName($name);
        if (empty($rule)) {
            return true;
        }
        if ($rule['status'] === 0) {
            return false;
        }
        $group = (new AuthGroupModel())->getGroupById($groupId);
        if ($group['status'] != 1 || empty($group['rules'])) {
            return false;
        }
        $myRuleIds = explode(',', $group['rules']);
        if (in_array($rule['id'], $myRuleIds)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取权限组权限 格式化成树状
     * @param $groupId 分组id
     * @return array
     */
    public function getAuthByGroupIdToTree($groupId)
    {
        $group = (new AuthGroupModel())->getGroupById($groupId);
        if ($group['status'] != 1 || empty($group['rules'])) {
            return [];
        }

        $ruleIds = explode(',', $group['rules']);
        $rules = (new  AuthRuleModel())->getRuleByIds($ruleIds);


        $rules = TreeUtil::listToTreeMulti($rules, 0, 'id', 'pid', 'children');

        return $rules;
    }


    /**
     * 获取所有的列表,不分页
     */
    public function getListsAll($status = -1, $myorder = 'a.id asc')
    {
        return (new AuthRuleModel())->getListsAll($status, $myorder);
    }

    /**
     * 获取列表
     */
    public function getLists($title = '', $status = -1, $myorder = 'a.sorts desc')
    {
        $res = (new AuthRuleModel())->getLists($title, $status, $myorder);
        return $res;
    }



    /** 通过ID获取信息
     * @param $id
     */
    public function getRuleById($id)
    {
        return (new AuthRuleModel())->getRuleById($id);
    }

    /** 通过标识获取信息
     * @param $name
     * @return mixed
     */
    public function getRuleByName($name)
    {
        return (new AuthRuleModel())->getRuleByName($name);
    }


    /**
     * 获取数量
     */
    public function getTotal($title = '', $status = -1)
    {
        return (new AuthRuleModel())->getTotal($title, $status);
    }

    /** 保存
     * @param $id
     * @param $data
     */
    public function modify($id, $data)
    {
        $data['updateTime'] = time();
        if ($id) {
            if ((new AuthRuleModel())->check($data['name']) && $id != (new AuthRuleModel())->check($data['name'])) {
                ajax_return_error('该标识已存在!');
            }
            return (new AuthRuleModel())->modify($id, $data);
        } else {
            if ((new AuthRuleModel())->check($data['name'])) {
                ajax_return_error('该标识已存在!');
            }
            return (new AuthRuleModel())->add($data);
        }
    }

    /** 删除
     * @param $id
     * @return int
     */
    public function del($id)
    {
        $info = (new AuthRuleModel())->getRuleByPid($id);
        if ($info) {
            ajax_return_error('请先删除下级!');
        }
        return (new AuthRuleModel())->del($id);
    }

    /**
     * @param $val id 值
     * @param $field 修改字段
     * @param $value 字段值
     */
    public function change($val, $field, $value)
    {
        $table = 'auth_rule';
        $id = 'id';
        return (new AdminModel())->change($table, $id, $val, $field, $value);
    }

    public function sort($listOrder)
    {
        if (empty($listOrder)) {
            ajax_return_error('没有数据！');
        } else {

            foreach ($listOrder as $id => $sorts) {
                (new AuthRuleModel())->sort($id, $sorts);
            }
            return true;
        }
    }

}
