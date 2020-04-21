<?php
/**
 * Created by PhpStorm.
 * User: lzx
 * Date: 2020-04-19
 * Time: 16:32
 */

namespace app\admin\services;

use app\admin\model\AuthGroupModel;

/**
 * Class AuthGroupService
 * @package app\admin\services
 * 权限角色
 */
class AuthGroupService
{
    public function getLists($title)
    {
        $res = (new AuthGroupModel())->getLists($title);
        return $res;
    }

    /** 通过ID获取信息
     * @param $id
     */
    public function getGroupById($id)
    {
        return (new AuthGroupModel())->getGroupById($id);
    }

}