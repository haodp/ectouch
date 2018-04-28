mkdir support

mv lang support/lang
mv tpl support/tpl

mv library/think/* ./

// mv base.php support/base.php
mv convention.php config/convention.php
mv helper.php support/helper.php

mv library/traits support/traits

rm -rf library

phpstorm 正则替换
\/\/ \+------(.*)\n\/\/ \|(.*)\n\/\/ \+------(.*)\n\/\/ \|(.*)\n\/\/ \+------(.*)\n\/\/ \|(.*)\n\/\/ \+------(.*)\n\/\/ \|(.*)\n\/\/ \+------(.*)\n

namespace think
namespace app\kernel

use think
use app\kernel

\think\\
\app\\kernel\\

\think\
\app\kernel\

think\\
app\\kernel\\

think\
app\kernel\

'root_node' => 'think',
'root_node' => 'root',

# base.php
facade\Config::set(include __DIR__ . '/convention.php');
facade\Config::set(include __DIR__ . '/config/convention.php');

移除以下代码：
// 载入Loader类
require __DIR__ . '/library/think/Loader.php';

// 注册自动加载
Loader::register();

// 加载composer autofile文件
Loader::loadComposerAutoloadFiles();



# helper
//------------------------
// ThinkPHP 助手函数
//-------------------------

[think]
[app]

Loader::parseName(
parse_name(


'name'    => 'Think Console',
'name'    => 'ECTouch Console',



__DIR__ . '/tpl
__DIR__ . '/../support/tpl


think_exception.tpl
exception.tpl




$this->thinkPath   = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR;
$this->thinkPath   = __DIR__ . DIRECTORY_SEPARATOR;