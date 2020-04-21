<?php
/**
 * Created by PhpStorm.
 * User: lzx
 * Date: 2020-04-20
 * Time: 12:06
 */

namespace app\admin\controller;


use app\admin\model\AuthInstructModel;

class AuthInstruct extends Base
{
    public function GetDataByList()
    {
        $data = input('param.');
        $res = AuthInstructModel::GetDataByList($data);
        ajax_return_ok($res);
    }

    public function PostDataBySave()
    {
        $data = input('param.');
        $check = AuthInstructModel::where('type', $data['type'])->where('rule_id', $data['rule_id'])->find();
        if (!empty($check)) {
            ajax_return_error('已经存在啦!!!');
        }
        $res = AuthInstructModel::create($data);
        ajax_return_ok($res);
    }

    public function GetIdByDelete()
    {
        $id = input('param.id', 0);
        $res = AuthInstructModel::where('id', $id)->delete();
        ajax_return_ok($res);
    }

}