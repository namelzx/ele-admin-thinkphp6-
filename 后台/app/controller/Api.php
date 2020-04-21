<?php
/**
 * Created by PhpStorm.
 * User: lzx
 * Date: 2020-04-15
 * Time: 06:38
 */

namespace app\controller;


use app\BaseController;
use app\Request;

use app\util\AuthUtil;


header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, authKey, sessionId, Access-Token, X-Token,x-token,x-access-appid");

class Api extends BaseController
{



    protected $request;

    /**
     * 构造方法
     * @param Request $request Request对象
     * @access public
     */

    public function __construct()
    {

        $this->request = \think\facade\Request::instance(); //初始化请求
        //跨域访问
//        if (config('app_debug') == true) {
        header("Access-Control-Allow-Origin:*");
        // 响应类型
        header("Access-Control-Allow-Methods:GET,POST");
        // 响应头设置
        header("Access-Control-Allow-Headers:x-requested-with,content-type,x-access-token,x-access-appid");
//        }

        //签名验证
        AuthUtil::checkSign();

    }
}