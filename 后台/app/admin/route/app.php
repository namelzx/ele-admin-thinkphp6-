<?php
/**
 * Created by PhpStorm.
 * User: lzx
 * Date: 2020-04-15
 * Time: 05:37
 */


use think\facade\Route;


//后台管理
Route::group('api/admin', function () {
    Route::rule('login', 'admin/login/login');
    Route::get('info', 'admin/admin/getuser');
    Route::post('logout', 'admin/admin/logout');

    //管理员模块
    Route::get('admin/GetDataByList', 'admin/admin/GetDataByList');//获取数据列表
    Route::get('admin/GetIdByInfo', 'admin/admin/GetIdByInfo');//获取详情
    Route::post('admin/PostDataBySave', 'admin/admin/PostDataBySave');//保存信息
    Route::get('admin/del', 'admin/admin/del');//删除数据


    //角色模块

    Route::get('roles/GetDataByList', 'admin/AuthGroup/GetDataByList');//获取角色列表
    Route::get('roles/GetDataByRule', 'admin/AuthGroup/GetDataByRule');//获取角色权限

    Route::post('roles/PostDataBySave', 'admin/AuthGroup/PostDataBySave');//保存角色权限


    //路由管理
    Route::rule('rules/index', 'admin/Authrule/index');
    Route::rule('rules/getListAll', 'admin/Authrule/getListAll');
    Route::rule('rules/getinfo', 'admin/Authrule/getinfo');
    Route::rule('rules/save', 'admin/Authrule/save');
    Route::rule('rules/del', 'admin/Authrule/del');
    Route::rule('rules/change', 'admin/Authrule/change');
    Route::rule('rules/delAll', 'admin/Authrule/delAll');
    Route::rule('rules/changeAll', 'admin/Authrule/changeAll');

    //路由指令管理
    Route::rule('Instruct/GetDataByList', 'admin/AuthInstruct/GetDataByList');
    Route::rule('Instruct/PostDataBySave', 'admin/AuthInstruct/PostDataBySave');
    Route::rule('Instruct/GetIdByDelete', 'admin/AuthInstruct/GetIdByDelete');


    //图片存储库
    Route::get('oss/GetDataByList', 'admin/OssImg/GetDataByList');//获取数据列表
    Route::get('oss/GetIdByInfo', 'admin/OssImg/GetIdByInfo');//获取详情
    Route::post('oss/PostDataBySave', 'admin/OssImg/PostDataByAdd');//保存信息
    Route::get('admin/del', 'admin/admin/del');//删除数据
});
