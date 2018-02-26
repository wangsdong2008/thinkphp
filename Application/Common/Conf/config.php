<?php
return array(
	//'配置项'=>'配置值'
    'MAIL_HOST' =>'smtp.163.com',//smtp服务器的名称
    'MAIL_SMTPAUTH' =>TRUE, //启用smtp认证
    'MAIL_USERNAME' =>'wangsdong2008@163.com',//发件人的邮箱名
    'MAIL_PASSWORD' =>'abc123',//163邮箱发件人授权密码
    'MAIL_FROM' =>'wangsdong2008@163.com',//发件人邮箱地址
    'MAIL_FROMNAME'=>'wangsdong',//发件人姓名
    'MAIL_CHARSET' =>'utf-8',//设置邮件编码
    'MAIL_ISHTML' =>TRUE, // 是否HTML格式邮件
    'DEFAULT_MODULE' => 'Home',//默认访问路径
    'MODULE_ALLOW_LIST' => array('Home','Users'),


    //数据库配置信息
    'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => 'localhost', // 服务器地址
    'DB_NAME'   => 'hb', // 数据库名
    'DB_USER'   => 'root', // 用户名
    'DB_PWD'    => 'root',
    'DB_PORT'   => 3306, // 端口
    'DB_PREFIX' => 'think_', // 数据库表前缀
    'DB_CHARSET'=> 'utf8', // 字符集

    'DB_DEBUG'  =>  false, // 数据库调试模式 开启后可以记录SQL日志 3.2.3新增
    'SESSION_AUTO_START' => true, //是否开启session
    'TMPL_DETECT_THEME' => true, // 自动侦测模板主题
    'URL_MODEL' => 2,
    'URL_HTML_SUFFIX'=>'.html',
    'DEFAULT_PATH' => '/Public/',
    'DEFAULT_FILTER' => 'htmlspecialchars',
    'ADMIN_DEFAULT_PAGENUM' => '10', //后台每页数量,
    'CATCH' => '0', //缓存,开启为1
    'CATCH_TIME' => 1,//缓存时间，单位天
    'COOKIE_HTTPONLY' => 1,
);