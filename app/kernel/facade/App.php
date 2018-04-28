<?php

namespace app\kernel\facade;

use app\kernel\Facade;

/**
 * @see \app\kernel\App
 * @mixin \app\kernel\App
 * @method \app\kernel\App bind(string $bind) static 绑定模块或者控制器
 * @method void initialize() static 初始化应用
 * @method void init(string $module='') static 初始化模块
 * @method \app\kernel\Response run() static 执行应用
 * @method \app\kernel\App dispatch(\app\kernel\route\Dispatch $dispatch) static 设置当前请求的调度信息
 * @method void log(mixed $log, string $type = 'info') static 记录调试信息
 * @method mixed config(string $name='') static 获取配置参数
 * @method \app\kernel\route\Dispatch routeCheck() static URL路由检测（根据PATH_INFO)
 * @method \app\kernel\App routeMust(bool $must = false) static 设置应用的路由检测机制
 * @method \app\kernel\Model model(string $name = '', string $layer = 'model', bool $appendSuffix = false, string $common = 'common') static 实例化模型
 * @method object controller(string $name, string $layer = 'controller', bool $appendSuffix = false, string $empty = '') static 实例化控制器
 * @method \app\kernel\Validate validate(string $name = '', string $layer = 'validate', bool $appendSuffix = false, string $common = 'common') static 实例化验证器类
 * @method \app\kernel\db\Query db(mixed $config = [], mixed $name = false) static 数据库初始化
 * @method mixed action(string $url, $vars = [], $layer = 'controller', $appendSuffix = false) static 调用模块的操作方法
 * @method string parseClass(string $module, string $layer, string $name, bool $appendSuffix = false) static 解析应用类的类名
 * @method string version() static 获取框架版本
 * @method bool isDebug() static 是否为调试模式
 * @method string getModulePath() static 获取当前模块路径
 * @method void setModulePath(string $path) static 设置当前模块路径
 * @method string getRootPath() static 获取应用根目录
 * @method string getAppPath() static 获取应用类库目录
 * @method string getRuntimePath() static 获取应用运行时目录
 * @method string getThinkPath() static 获取核心框架目录
 * @method string getRoutePath() static 获取路由目录
 * @method string getConfigPath() static 获取应用配置目录
 * @method string getConfigExt() static 获取配置后缀
 * @method string setNamespace(string $namespace) static 设置应用类库命名空间
 * @method string getNamespace() static 获取应用类库命名空间
 * @method string getSuffix() static 是否启用类库后缀
 * @method float getBeginTime() static 获取应用开启时间
 * @method integer getBeginMem() static 获取应用初始内存占用
 * @method \app\kernel\Container container() static 获取容器实例
 */
class App extends Facade
{
}
