<?php
/***
    ZipcodeXpress
    Author: richardz@zipcodexpress.com
    Date: 2026-03-31
***/

// 应用入口文件

// 检测PHP环境
if (version_compare(PHP_VERSION, '5.3.0', '<'))
    die('require PHP > 5.3.0 !');

// APP_STATUS 为状态配置, 首先从Common/Conf下加载,然后从子模块加载

// 服务器变量 UNIBOX_ENV
defined('APP_STATUS') or define('APP_STATUS', isset($_SERVER["UNIBOX_ENV"]) ?
    $_SERVER['UNIBOX_ENV'] : 'local');  // Changed to 'local' for local development

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
defined('APP_DEBUG') or define('APP_DEBUG', APP_STATUS != 'product');

/**
 * 默认采用的db驱动,优先使用mysqli
 */
define('USE_DB_EXT', extension_loaded('mysqli') ? 'mysqli' : 'mysql');

// 定义应用目录
define('UNIBOX_ROOT_PATH', dirname(__FILE__) . '/../');
// 定义应用目录
define('APP_PATH', UNIBOX_ROOT_PATH . 'Application/');

//require 'vendor/autoload.php';
define('RUNTIME_PATH', UNIBOX_ROOT_PATH . 'Runtime/');

//use composer autoload
require UNIBOX_ROOT_PATH . 'vendor/autoload.php';
// 引入ThinkPHP入口文件
require UNIBOX_ROOT_PATH . 'ThinkPHP/ThinkPHP.php';
