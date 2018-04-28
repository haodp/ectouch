<?php

namespace app\kernel\facade;

use app\kernel\Facade;

/**
 * @see \app\kernel\View
 * @mixin \app\kernel\View
 * @method \app\kernel\View init(mixed $engine = [], array $replace = []) static 初始化
 * @method \app\kernel\View share(mixed $name, mixed $value = '') static 模板变量静态赋值
 * @method \app\kernel\View assign(mixed $name, mixed $value = '') static 模板变量赋值
 * @method \app\kernel\View config(mixed $name, mixed $value = '') static 配置模板引擎
 * @method \app\kernel\View exists(mixed $name) static 检查模板是否存在
 * @method \app\kernel\View filter(Callable $filter) static 视图内容过滤
 * @method \app\kernel\View engine(mixed $engine = []) static 设置当前模板解析的引擎
 * @method string fetch(string $template = '', array $vars = [], array $replace = [], array $config = [], bool $renderContent = false) static 解析和获取模板内容
 * @method string display(string $content = '', array $vars = [], array $replace = [], array $config = []) static 渲染内容输出
 */
class View extends Facade
{
}
