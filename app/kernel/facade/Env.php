<?php

namespace app\kernel\facade;

use app\kernel\Facade;

/**
 * @see \app\kernel\Env
 * @mixin \app\kernel\Env
 * @method void load(string $file) static 读取环境变量定义文件
 * @method mixed get(string $name = null, mixed $default = null) static 获取环境变量值
 * @method void set(mixed $env, string $value = null) static 设置环境变量值
 */
class Env extends Facade
{
}
