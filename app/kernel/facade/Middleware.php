<?php

namespace app\kernel\facade;

use app\kernel\Facade;

/**
 * @see \app\kernel\Middleware
 * @mixin \app\kernel\Middleware
 * @method void import(array $middlewares = []) static 批量设置中间件
 * @method void add(mixed $middleware) static 添加中间件到队列
 * @method void unshift(mixed $middleware) static 添加中间件到队列开头
 * @method array all() static 获取中间件队列
 * @method \app\kernel\Response dispatch(\app\kernel\Request $request) static 执行中间件调度
 */
class Middleware extends Facade
{
}
