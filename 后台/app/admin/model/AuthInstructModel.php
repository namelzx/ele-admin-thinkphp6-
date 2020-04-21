<?php
/**
 * Created by PhpStorm.
 * User: lzx
 * Date: 2020-04-20
 * Time: 12:04
 */

namespace app\admin\model;


use think\Model;

class AuthInstructModel extends Model
{
    protected $table = 'tp_auth_instruct';


    public function getTypeAttr($value)
    {
        $status = [1 => '搜索', 2 => '删除', 3 => '修改', 4 => '禁用', 5=> '导出'];
        return $status[$value];
    }

    public static function GetDataByList($data)
    {

        $where = [];
        if (!empty($data['title'])) {
            $where[] = ['title', 'like', '%' . $data['title'] . '%'];
        }
        return self::where($where)->paginate($data['limit'], false, ['page' => $data['page']]);
    }

}