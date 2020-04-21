<?php
/**
 * Created by PhpStorm.
 * User: lzx
 * Date: 2020-04-19
 * Time: 14:30
 */

namespace app\admin\services;


use app\admin\model\AdminModel;

class AdminService
{
    public function getLists($userName = '', $phone = '', $realName = '', $startTime = '', $endTime = '', $isEnabled = -1, $myorder = 'a.id desc', $page = 1, $limit = 10, $groupId = null)
    {
        $res = (new AdminModel())->getLists($userName, $phone, $realName, $startTime, $endTime, $isEnabled, $myorder, $page, $limit, $groupId);
        return $res;
    }

    public function getInfo($id)
    {
        $res = (new AdminModel())->getAdminById($id);
        return $res;
    }

    public function modify($data)
    {
        if ($data['id'] === 0) {
            $res = (new AdminModel())->add($data);
            return $res;
        }
        $res = (new AdminModel())->modify($data['id'], $data);
        return $res;
    }
    public function del($id)
    {
        $res = (new AdminModel())->del($id);
        return $res;
    }
}