<?php

namespace app\kernel\facade;

use app\kernel\Facade;

/**
 * @see \app\kernel\Build
 * @mixin \app\kernel\Build
 * @method void run(array $build = [], string $namespace = 'app', bool $suffix = false) static 根据传入的build资料创建目录和文件
 * @method void module(string $module = '', array $list = [], string $namespace = 'app', bool $suffix = false) static 创建模块
 */
class Build extends Facade
{
}
