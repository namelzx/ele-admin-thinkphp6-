<?php
/**
 * Created by PhpStorm.
 * User: lzx
 * Date: 2020-04-08
 * Time: 08:37
 */

namespace app\admin\controller;


use app\admin\services\AdminLoginService;
use app\controller\Api;

class Login extends Api
{
    public function index()
    {
        ajax_return_ok(44);
    }

    public function login()
    {
        if ($this->request->isPost()) {
            //接收数据
            $data = [
                'userName' => input('username', '', 'trim'),
                'password' => input('password', '', 'trim'),
            ];
            $validate = validate('Admin');
            $result = $validate->scene('login')->check($data);
            if (!$result) {
                $error = $validate->getError();
                ajax_return_error($error);
            }
//            // 登录验证并获取包含访问令牌的用户
            $result = (new AdminLoginService())->login($data);
            ajax_return_ok($result, '登录成功');
        }


    }


}