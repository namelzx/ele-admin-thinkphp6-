<?php
/**
 * Created by PhpStorm.
 * User: lzx
 * Date: 2020-04-08
 * Time: 08:40
 */

namespace app\util;


use Firebase\JWT\JWT;
use think\facade\Env;

class JwtUtil
{

    /**
     * 使用配置文件配置的密钥和算法编码
     * @param object|array $payload 代表JWT's payload的对象或数组
     * @return string 已编码的json web tooken字符串
     * @throws Think\Exception 读取配置失败时
     */
    public static function encode($payload)
    {
        // 读取配置
        $secretKey = Env::get('database.jwt_secret_key');
        $algorithm = Env::get('database.jwt_algorithm');
        if (!$secretKey || !$algorithm) {
            my_exception('JWT配置缺失', CommonConstant::e_system_config_miss);
        }

        // 使用Firebase JWT解码并返回
        return JWT::encode($payload, $secretKey, $algorithm);
    }

    /**
     * 使用配置文件配置的密钥和算法解码
     * @param string $jwt 已编码的json web tooken字符串
     * @return object|boolean 签名认证通过时，代表JWT's payload的对象；解码或签名认证失败时，false；
     * @throws Think\Exception 读取配置失败时
     */
    public static function decode($jwt)
    {
//        $jwt = think_decrypt($jwt);

        $secretKey = Env::get('database.jwt_secret_key');
        $algorithm = Env::get('database.jwt_algorithm');

        if (!$secretKey || !$algorithm) {
            my_exception('JWT配置缺失', CommonConstant::e_system_config_miss);
        }
        // 使用Firebase JWT解码
        try {
            $decode = JWT::decode($jwt, $secretKey, array($algorithm));
            return $decode;
        } catch (\think\Exception $e) {
            Log::record('[JWT配置缺失]' . $e->__toString(), Log::INFO);
            return false;
        }
    }

}