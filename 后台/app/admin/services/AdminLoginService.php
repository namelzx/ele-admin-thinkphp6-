<?php
/**
 * Created by PhpStorm.
 * User: lzx
 * Date: 2020-04-15
 * Time: 06:10
 */

namespace app\admin\services;


use app\admin\model\AdminModel;
use app\admin\model\AuthGroupModel;
use app\admin\model\LoginLogModel;
use app\util\CommonConstant;
use app\util\JwtUtil;

class AdminLoginService
{
    public function login($data)
    {
        $user = (new AdminModel())->getAdminByName($data['userName']);
        if (empty($user)) {//当用户不存在
            my_exception('', CommonConstant::e_user_miss);
        }
        //密码验证
        if ($user['password'] !== $data['password']) {
            my_exception('', CommonConstant::e_user_pass_wrong);
        }
        // 检测用户状态
        if ($user ['isEnabled'] != CommonConstant::db_true) {
            my_exception('', CommonConstant::e_user_disabled);
        }
        //权限检测
        $group = (new AuthGroupModel())->getGroupById($user['groupId']);
        if (empty($group) || $group['status'] != 1) {
            my_exception('用户组不存在或被禁用！', CommonConstant::e_user_role_disabled);
        }

        // 数据处理和令牌获取
        $time = time();
        // 记录登录日志
        (new LoginLogModel())->add([
            'uid' => $user['id'],
            'userName' => $user['userName'],
            'roles' => $group['title'],
            'loginTime' => $time,
            'loginIp' => request()->ip()
        ]);

        (new AdminModel())->modify($user['id'], ['loginTime' => $time]);
        // 令牌生成
        $payload['uid'] = $user['id'];
        $payload['loginTime'] = $time;
        $userToken = think_encrypt(JwtUtil::encode($payload));
        // 返回
        return array('userToken' => $userToken);
    }

}