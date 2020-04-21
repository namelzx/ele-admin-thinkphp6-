<?php
/**
 * Created by PhpStorm.
 * User: lzx
 * Date: 2020-04-15
 * Time: 06:34
 */

namespace app\admin\controller;


use app\admin\services\AdminService;
use app\admin\services\AuthRuleService;

/**
 * Class Admin
 * @package app\admin\controller
 * 管理员信息
 */
class Admin extends Base
{


    public function GetDataByList()
    {
        if ($this->request->isGet()) {
            //搜索参数
            $isEnabled = input('isEnabled', -1, 'intval');
            $userName = input('userName', '', 'trim');
            $phone = input('phone', '', 'trim');
            $realName = input('realName', '', 'trim');
            $startTime = input('startTime', '', 'strtotime');
            $endTime = input('endTime', '', 'strtotime');
            $order = input('order/a', 'a.id desc');
            $page = input('page', 1, 'intval');
            $limit = input('limit', 10, 'intval');
            $groupId = input('groupId', 0, 'intval');
            $result = (new AdminService())->getLists($userName, $phone, $realName, $startTime, $endTime, $isEnabled, $order, $page, $limit, $groupId);
            ajax_return_ok($result);
        }
    }

    //获取登录用户信息
    public function getuser()
    {


        $user = $this->user;


        $access = (new AuthRuleService())->getAuthByGroupIdToTree($user['groupId']);


        $routers = [];


        $access = $this->bubble_sort($access);
        foreach ($access as $v) {
            $temp = $this->getdata($v);
            foreach ($v['children'] as $vo => $ivo) {
                $temp['children'][] = $this->getdata($ivo);
                if (!empty($ivo['children'])) {
                    $children = $this->bubble_sort($ivo['children']);
                    $temp['children'][$vo]['children'] = $this->getMuen($children);
                }
            }
            $routers[] = $temp;
        }
        $user['access'] = $routers;

//        $user['group'] = my_model('AuthGroup', 'model', 'admin')->getGroupById($user['groupId']);

        ajax_return_ok($user);
    }

    protected function bubble_sort($arr)
    {
        $len = count($arr);
        for ($i = 0; $i < $len - 1; $i++) {//循环对比的轮数
            for ($j = 0; $j < $len - $i - 1; $j++) {//当前轮相邻元素循环对比
//                if ($arr[$j]['sorts'] > $arr[$j + 1]['sorts']) {//如果前边的大于后边的
//                    $tmp = $arr[$j];//交换数据
//                    $arr[$j] = $arr[$j + 1];
//                    $arr[$j + 1] = $tmp;
//                }
            }
        }
        return $arr;
    }

    protected function getMuen($idata)
    {
        $temp = [];
        foreach ($idata as $i => $data) {
            $temp[$i]['path'] = $data['path'];
            $temp[$i]['component'] = $data['component'];
            $temp[$i]['name'] = $data['name'];
            if ($data['hidden'] > -1) {
                $temp[$i]['hidden'] = (boolean)$data['hidden'];
            }
            if ($data['alwaysShow'] > -1) {
                $temp[$i]['alwaysShow'] = (boolean)$data['alwaysShow'];
            }

            if ($data['redirect']) {
                $temp[$i]['redirect'] = $data['redirect'];
            }
            $temp[$i]['meta']['title'] = $data['title'];
            $temp[$i]['meta']['icon'] = $data['icon'];
            if ($data['noCache'] > -1) {
                $temp[$i]['meta']['noCache'] = (boolean)$data['noCache'];
            }
        }

        return $temp;
    }

    protected function getdata($data)
    {

        $temp = [];
        $temp['path'] = $data['path'];
        $temp['component'] = $data['component'];
        $temp['name'] = $data['name'];
        if ($data['hidden'] > -1) {
            $temp['hidden'] = (boolean)$data['hidden'];
        }
        if ($data['alwaysShow'] > -1) {
            $temp['alwaysShow'] = (boolean)$data['alwaysShow'];
        }
        if ($data['redirect']) {
            $temp['redirect'] = $data['redirect'];
        }
        $temp['meta']['title'] = $data['title'];
        $temp['meta']['icon'] = $data['icon'];
        $temp['id'] = $data['id'];
        if ($data['noCache'] > -1) {
            $temp['meta']['noCache'] = (boolean)$data['noCache'];
        }


        return $temp;
    }

    public function logout()
    {
        ajax_return_ok();
    }


    /**
     * 通过id得到信息
     */
    public function GetIdByInfo()
    {
        $id = input('param.id', 0);
        $res = (new AdminService())->getInfo($id);
        ajax_return_ok($res);
    }

    /**
     * 保存信息
     */
    public function PostDataBySave()
    {
        $data = input('param.');
        $res = (new AdminService())->modify($data);
        ajax_return_ok($res, '保存成功');
    }

    public function del()
    {
        $id = input('param.id', 0);
        $res = (new AdminService())->del($id);
        ajax_return_ok($res,'删除成功');
    }

}