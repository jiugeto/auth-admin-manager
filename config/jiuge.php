<?php

/**
 * jiuge 后台权限管理配置文件
 */

return array(
    'title' => '你的后台主题',//主页标题
    'icon' => 'favicon.ico',//小图标
    'pub' => '/public-admin-manager/',//模板文件根目录
    //登陆信息
    'loginUserName' => '管理员名称',
    'loginPwd' => '管理员密码',
    'adminUrl' => '/admin',//后台路由
    'footer' => '你的页脚信息。',//页脚信息
    'notice' => array(//公告信息
        'name' => '公告',
        'content' => '时光静好，与君语；细水流年，与君同。—— Amaze UI',
    ),
    'wiki' => array(
        'name' => 'wiki',
        'content' => 'Welcome to the Amaze UI wiki!',
    ),
);