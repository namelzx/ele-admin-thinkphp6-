<?php
/**
 * Created by PhpStorm.
 * User: lzx
 * Date: 2020-04-15
 * Time: 06:00
 */

namespace app\admin\validate;


use think\Validate;

class Admin extends Validate
{
    protected $rule = [
        'userName' => 'require|max:25',
        'password' => 'require',

    ];
//
    protected $message = [
        'userName.require' => '名称必须',
        'password.require' => '密码不可为空',
    ];

    protected $scene = [
        'login' => ['userName', 'password'],
    ];

}