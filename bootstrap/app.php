<?php

/**
 * ECTouch - E-Commerce Platform for PHP.
 *
 * @package  ECTouch
 * @homepage https://www.ectouch.cn
 */

/*
|--------------------------------------------------------------------------
| 运行环境
|--------------------------------------------------------------------------
*/

define('IN_ECTOUCH', true);

/*
|--------------------------------------------------------------------------
| 应用名称
|--------------------------------------------------------------------------
*/

define('APPNAME', 'ECTouch');

/*
|--------------------------------------------------------------------------
| 应用版本
|--------------------------------------------------------------------------
*/

define('VERSION', 'v3.0.0');

/*
|--------------------------------------------------------------------------
| 发布时间
|--------------------------------------------------------------------------
*/

define('RELEASE', '20180312');

/*
|--------------------------------------------------------------------------
| 编码格式
|--------------------------------------------------------------------------
*/

define('CHARSET', 'utf-8');

/*
|--------------------------------------------------------------------------
| 控制台入口
|--------------------------------------------------------------------------
*/

define('ADMIN_PATH', 'dash');

/*
|--------------------------------------------------------------------------
| 授权密钥
|--------------------------------------------------------------------------
*/

define('AUTH_KEY', 'this is a key');

/*
|--------------------------------------------------------------------------
| 授权密钥备份
|--------------------------------------------------------------------------
*/

define('OLD_AUTH_KEY', '');

/*
|--------------------------------------------------------------------------
| API 更新时间
|--------------------------------------------------------------------------
*/

define('API_TIME', '2017-08-02 09:20:18');

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels great to relax.
|
*/

require __DIR__ . '/../vendor/topthink/framework/base.php';

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new ECTouch application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = think\Container::get('app');

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;