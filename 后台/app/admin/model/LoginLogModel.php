<?php
/**
 * Created by PhpStorm.
 * User: lzx
 * Date: 2020-04-15
 * Time: 06:30
 */

namespace app\admin\model;


use think\Model;

class LoginLogModel extends Model
{
    protected $table='tp_login_log';

    /** 添加日志
     * @param $data
     */
    public function add($data)
    {
        return $this->insertGetId($data);
    }

    /**
     * 获取管理员列表
     */
    public function getLists($data)
    {
        $where = [];
        if (!empty($data['uid'])) {
            $where[] = ['uid', 'eq', $data['uid']];
        }
        if (!empty($data['userName'])) {
            $where[] = ['userName', 'eq', $data['userName']];
        }
        if (!empty($data['loginIp'])) {
            $where[] = ['loginIp', 'eq', $data['loginIp']];
        }
        if (!empty($data['loginIp'])) {
            $where[] = ['loginIp', 'eq', $data['loginIp']];
        }
        if (!empty($data['startTime'])) {
            $where[] = ['loginTime', 'between time', [$data['startTime'], $data['endTime']]];
        }
        return $this->alias('a')
            ->order('id desc')
            ->where($where)
            ->paginate($data['psize'], false, ['query' => [
                'page' => $data['page']
            ]]);
    }

}