<?php

namespace app\kernel\facade;

use app\kernel\Facade;

/**
 * @see \app\kernel\Response
 * @mixin \app\kernel\Response
 * @method \app\kernel\response create(mixed $data = '', string $type = '', int $code = 200, array $header = [], array $options = []) static 创建Response对象
 * @method void send() static 发送数据到客户端
 * @method \app\kernel\Response options(mixed $options = []) static 输出的参数
 * @method \app\kernel\Response data(mixed $data) static 输出数据设置
 * @method \app\kernel\Response header(mixed $name, string $value = null) static 设置响应头
 * @method \app\kernel\Response content(mixed $content) static 设置页面输出内容
 * @method \app\kernel\Response code(int $code) static 发送HTTP状态
 * @method \app\kernel\Response lastModified(string $time) static LastModified
 * @method \app\kernel\Response expires(string $time) static expires
 * @method \app\kernel\Response eTag(string $eTag) static eTag
 * @method \app\kernel\Response cacheControl(string $cache) static 页面缓存控制
 * @method \app\kernel\Response contentType(string $contentType, string $charset = 'utf-8') static 页面输出类型
 * @method mixed getHeader(string $name) static 获取头部信息
 * @method mixed getData() static 获取原始数据
 * @method mixed getContent() static 获取输出数据
 * @method int getCode() static 获取状态码
 */
class Response extends Facade
{
}
