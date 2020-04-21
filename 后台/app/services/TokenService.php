<?php
/**
 * Created by PhpStorm.
 * User: lzx
 * Date: 2020-03-28
 * Time: 05:20
 */

namespace app\services;

use Firebase\JWT\JWT;

class TokenService
{

    private $tokenUrl;
    private $xcxTokenUrl;
    const TOKEN_CACHED_KEY = 'access';
    const TOKEN_EXPIRE_IN = 7000;

    function __construct()
    {

//        $arr=SysConfig::where(['type'=>2])->select();
//        $cg=[];
//        foreach ($arr as $k=>$v){
//            if(in_array($v['key'],['gzh_appid','gzh_secret','wx_app_id','wx_app_secret'])){
//                $cg[$v['key']]=$v['value'];
//            }
//        }
//        if(empty($cg['gzh_appid']) && empty($cg['wx_appid'])) {
//            throw new BaseException(['msg'=>'缺少appid']);
//        }
//        if(empty($cg['gzh_secret']) && empty($cg['wx_app_secret'])) {
//            throw new BaseException(['msg'=>'缺少secret']);
//        }
//        $appid=$cg['gzh_appid']?$cg['gzh_appid']:$cg['wx_app_id'];
//        $secret=$cg['gzh_secret']?$cg['gzh_secret']:$cg['wx_app_secret'];
//
//        $access_token_url = config('setting.access_token_url');
//        $url = sprintf($access_token_url, $appid,$secret);
//        $this->tokenUrl = $url;
//        $xcx_url=sprintf($access_token_url, $cg['wx_app_id'],$cg['wx_app_secret']);
//        $this->xcxTokenUrl = $xcx_url;
    }

    public static function GTadmimScope($token)
    {
        $key = "example_key";

        $decoded = JWT::decode($token, $key, array('HS256')); //揭秘
      ajax_return_error('验证错误');
    }


}