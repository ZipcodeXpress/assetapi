<?php
/***
    ZipcodeXpress
    Author: liuyuan@unibox.com.cn
    Date: 2017-10-17
***/

// 应用入口文件

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

// APP_STATUS 为状态配置, 首先从Common/Conf下加载,然后从子模块加载

// 参数 UNIBOX_ENV
$env = isset($argv[2]) ? $argv[2] : '';
if (!in_array($env, array('dev','product'))) {
    exit("请在第二个参数填写正确的环境变量： dev, product\n");
}
defined('APP_STATUS') or define('APP_STATUS', $env);

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
defined('APP_DEBUG') or define('APP_DEBUG', APP_STATUS != 'product');
/**
 * 默认采用的db驱动,优先使用mysqli
 */
define('USE_DB_EXT', extension_loaded('mysqli') ? 'mysqli' : 'mysql');

define('UNIBOX_ROOT_PATH', dirname(__file__) . '/../');
// 定义应用目录
define('APP_PATH',UNIBOX_ROOT_PATH . 'Application/');

define('RUNTIME_PATH', UNIBOX_ROOT_PATH . 'Runtime/');

//use composer autoload
require UNIBOX_ROOT_PATH . 'vendor/autoload.php';
// 引入ThinkPHP入口文件
require UNIBOX_ROOT_PATH . 'ThinkPHP/ThinkPHP.php';
