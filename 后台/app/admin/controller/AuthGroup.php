<?php
/**
 * Created by PhpStorm.
 * User: lzx
 * Date: 2020-04-19
 * Time: 16:31
 */

namespace app\admin\controller;


use app\admin\model\AuthGroupModel;
use app\admin\model\AuthInstructModel;
use app\admin\services\AuthGroupService;
use app\admin\services\AuthRuleService;

class AuthGroup extends Base
{
    public function GetDataByList()
    {
        if ($this->request->isGet()) {
            //搜索参数
            $title = input('title', '');

            $result = (new AuthGroupService())->getLists($title);
            ajax_return_ok($result);
        }

    }

    /**
     * 获取角色权限
     */
    public function GetDataByRule()
    {
        $id = input('id', 0, 'intval');
        $temp['group'] = (new AuthGroupService())->getGroupById($id);
        $temp['Rule'] = $this->listins((new AuthRuleService())->getListsAll(1));
        ajax_return_ok($temp);
    }

    /**
     * 获取按钮权限
     */
    public function listins($data)
    {
        $arr = [];
        foreach ($data as $i => $item) {
            $arr[$i] = $item;
            $arr[$i]['bm'] = AuthInstructModel::where('rule_id', $item['id'])->select();
        }
        return $arr;
    }

    public function PostDataBySave()
    {
        $data = input('param.');
        $temp = [
            'rules' => implode(',', $data['checkList']),
            'btn' => implode(',', $data['rolesList'])
        ];
        $res = AuthGroupModel::where('id', $data['id'])->data($temp)->save();
        ajax_return_ok($res);
    }


}