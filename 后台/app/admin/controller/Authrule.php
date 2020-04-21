<?php
/**
 * Created by PhpStorm.
 * User: lzx
 * Date: 2020-04-19
 * Time: 17:33
 */

namespace app\admin\controller;


use app\admin\services\AuthRuleService;

class Authrule extends Base
{

    /**
     * 列表
     */
    public function index()
    {
//        if ($this->request->isPost()) {
            //搜索参数
            $title          = input('title', '', 'trim');
            $status         = input('status', -1, 'int');
            $order          = input('order/a', '');
            $lists          = (new AuthRuleService())->getLists($title, $status, $order);
            $result['data'] = $lists;
            ajax_return_ok($result);
//        }
    }

    /**
     * 列表,不分页
     */
    public function getLists()
    {
        if ($this->request->isPost()) {
            $lists = (new AuthRuleService())->getListsAll(1);
            ajax_return_ok($lists);
        }
    }


    /** 详情
     * @return mixed
     */
    public function getinfo()
    {
        $id = input('id', '0', 'int');
        if ($id == 0) {
            ajax_return_error('参数有误！');
        }
        $info =  (new AuthRuleService())->getRuleById($id);
        ajax_return_ok($info);
    }

    /**
     * 保存
     */
    public function save()
    {
        $id = input('id', '0', 'int');
        //接收数据
        $data     = [
            'pid'        => input('pid', 0, 'int'),

            'sorts'        => input('sorts', 0, 'int'),

            'title'      => input('title', '', 'trim'),
            'name'       => input('name', '', 'trim'),
            'icon'       => input('icon', '', 'trim'),
            'status'     => input('status', 0, 'int'),
            'path'       => input('path', '', 'trim'),
            'component'  => input('component', '', 'trim'),
            'redirect'   => input('redirect', '', 'trim'),
            'hidden'     => input('hidden', 0, 'int'),
            'noCache'    => input('noCache', 1, 'int'),
            'alwaysShow' => input('alwaysShow', 1, 'int')
        ];
        $validate = validate('AuthRule');
        $result   = $validate->check($data);
        if (!$result) {
            $error = $validate->getError();
            ajax_return_error($error);
        }

        if ($id && $id == $data['pid']) {
            ajax_return_error('上级不能选择自己！');
        }

        $res = (new AuthRuleService())->modify($id, $data);
        if ($res == false) {
            ajax_return_error('保存失败！');
        } else {
            ajax_return_ok(['id'=>$res], '保存成功！');
        }
    }

    /**
     * 删除
     */
    public function del()
    {
        $id = input('id', '0', 'int');
        if ($id == 0) {
            ajax_return_error('参数有误！');
        } else {
            if ((new AuthRuleService())->del($id)) {
                ajax_return_ok([], '删除成功！');
            } else {
                ajax_return_error('删除失败！');
            }
        }
    }

    public function change()
    {
        $val   = input('val', '', 'int');
        $field = input('field', '', 'trim');
        $value = input('value', '', 'int');
        if (empty($field)) {
            ajax_return_error('参数有误！');
        }
        $res = (new AuthRuleService())->change($val, $field, $value);
        if ($res) {
            ajax_return_ok([], '修改成功！');
        } else {
            ajax_return_error('修改失败！');
        }

    }

    /**
     * [sortColum 栏目排序]
     * @return [type] [description]
     */
    public function sort()
    {
        $listOrder = input('listOrder/a');
        $res       = (new AuthRuleService())->sort($listOrder);
        ajax_return_ok([], '排序成功！');
    }

    /**
     * 批量删除
     */
    public function delall()
    {
        $ids = input('ids', '', 'trim');
        if (empty($ids)) {
            ajax_return_error('参数有误！');
        } else {
            $ids = explode(',', $ids);
            foreach ($ids as $v) {
                $res = (new AuthRuleService())->del($v);
            }
            ajax_return_ok([], '删除成功！');
        }
    }

    public function changeall()
    {
        $val   = input('val', '', 'trim');
        $field = input('field', '', 'trim');
        $value = input('value', '', 'int');
        if (empty($val)) {
            ajax_return_error('参数有误！');
        }
        if (empty($field)) {
            ajax_return_error('参数有误！');
        }

        $ids = explode(',', $val);
        foreach ($ids as $v) {
            $res = (new AuthRuleService())->change($v, $field, $value);
        }

        ajax_return_ok([], '修改成功！');

    }

}