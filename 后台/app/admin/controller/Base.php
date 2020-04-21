<?php
/**
 * Created by PhpStorm.
 * User: lzx
 * Date: 2020-04-15
 * Time: 06:42
 */

namespace app\admin\controller;


use app\admin\services\AuthRuleService;
use app\controller\Api;
use app\util\AuthUtil;
use think\App;

class Base extends Api
{
    //用户信息
    protected $user = [];
    protected $uid = 0;

    public function __construct(App $app = null)
    {
        parent::__construct($app);

        //身份验证
        $result = AuthUtil::checkUser('admin');
        if ($result['status']) {
            $this->user = $result['msg'];
            $this->uid = $result['msg']['id'];
        }
        //权限验证
//        $module = strtolower($this->re);
//        $controller = strtolower(request()->controller());
//        $action = strtolower(request()->action());
//        $nowUrl = $module . '/' . $controller . '/' . $action;
        $nowUrl = 'manage';
        $access = (new AuthRuleService())->hasAccessByName($nowUrl, $result['msg']['groupId']);
        if (!$access) {
            ajax_return_error('无权访问！');
        }
    }

    public function _initialize()
    {


        parent::_initialize();


    }

}