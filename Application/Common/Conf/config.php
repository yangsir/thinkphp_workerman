<?php
return array(
	//'配置项'=>'配置值'
    'DEFAULT_MODULE'     => 'Home', //默认模块
    'MODULE_ALLOW_LIST'    => array('Home'),
    'MODULE_DENY_LIST'     => array('Common', 'Runtime', 'Workerman'),
    'URL_MODEL'            => 2,

    'RPC_ADDRESS' => array(
        'tcp://127.0.0.1:10086',
        'tcp://127.0.0.1:10086',
    ),

    'DB_TYPE'          => 'mysql', // 数据库类型
    'DB_HOST'          => '127.0.0.1', // 服务器地址
    'DB_NAME'          => 'webyang', // 数据库名
    'DB_USER'          => 'root', // 用户名
    'DB_PWD'           => '123456', // 密码
    'DB_PORT'          => '3306', // 端口
    'DB_PREFIX'        => 'wy_', // 数据库表前缀
    'DB_CHARSET'       => 'utf8', // 字符集
);
