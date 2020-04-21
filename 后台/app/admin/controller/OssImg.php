<?php
/**
 * Created by PhpStorm.
 * User: lzx
 * Date: 2020-04-21
 * Time: 11:43
 */

namespace app\admin\controller;


use app\admin\model\OssImgModel;

class OssImg extends Base
{

    public function GetDataByList()
    {
        $data = input('param.');
        $where = [];
        $res = OssImgModel::where($where)->paginate($data['limit'], false, ['page' => $data['page']]);
        ajax_return_ok($res);
    }

    public function PostDataByAdd()
    {
        $data = input('param.');
        $res = OssImgModel::create($data);
        ajax_return_ok($res);
    }

}