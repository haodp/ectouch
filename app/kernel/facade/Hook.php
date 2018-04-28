<?php

namespace app\kernel\facade;

use app\kernel\Facade;

/**
 * @see \app\kernel\Hook
 * @mixin \app\kernel\Hook
 * @method \app\kernel\Hook alias(mixed $name, mixed $behavior = null) static 指定行为标识
 * @method void add(string $tag, mixed $behavior, bool $first = false) static 动态添加行为扩展到某个标签
 * @method void import(array $tags, bool $recursive = true) static 批量导入插件
 * @method array get(string $tag = '') static 获取插件信息
 * @method mixed listen(string $tag, mixed $params = null, bool $once = false) static 监听标签的行为
 * @method mixed exec(mixed $class, mixed $params = null) static 执行行为
 */
class Hook extends Facade
{
}
