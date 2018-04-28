<?php

namespace app\kernel\facade;

use app\kernel\Facade;

/**
 * @see \app\kernel\Url
 * @mixin \app\kernel\Url
 * @method string build(string $url = '', mixed $vars = '', mixed $suffix = true, mixed $domain = false) static URL生成 支持路由反射
 * @method void root(string $root) static 指定当前生成URL地址的root
 */
class Url extends Facade
{
}
