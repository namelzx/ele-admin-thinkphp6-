<?php

namespace app\controller;

use app\BaseController;
use app\Request;
use tp5er\Backup;

class Index extends BaseController
{
    public function index(Request $request)
    {

        $config = array(
            'path' => './Data/',//数据库备份路径
            'part' => 20971520,//数据库备份卷大小
            'compress' => 0,//数据库备份文件是否启用压缩 0不压缩 1 压缩
            'level' => 9 //数据库备份文件压缩级别 1普通 4 一般  9最高
        );
        $db = new Backup($config);
        $file='001';
        $tab=$db->dataList();

        $tables="数据库表1";
        $start= $db->setFile($tables)->backup($tab[1], 0);
        ajax_return_ok($start);


    }

    public function hello($name = 'ThinkPHP6')
    {
        return 'hello,' . $name;
    }


}
