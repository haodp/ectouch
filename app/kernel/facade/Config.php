<?php

namespace app\kernel\facade;

use app\kernel\Facade;

/**
 * @see \app\kernel\Config
 * @mixin \app\kernel\Config
 * @method bool has(string $name) static 检测配置是否存在
 * @method array pull(string $name) static 获取一级配置
 * @method mixed get(string $name) static 获取配置参数
 * @method mixed set(string $name, mixed $value = null) static 设置配置参数
 * @method array reset(string $prefix ='') static 重置配置参数
 */
class Config extends Facade
{
}
