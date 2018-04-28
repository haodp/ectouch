<?php

/**
 * 应用根目录
 * @param string $path
 * @return string
 */
function base_path($path = '')
{
    return dirname(dirname(__DIR__)) . ($path ? DIRECTORY_SEPARATOR . $path : $path);
}

/**
 * 应用核心目录
 * @param string $path
 * @return string
 */
function app_path($path = '')
{
    return base_path('app' . ($path ? DIRECTORY_SEPARATOR . $path : $path));
}

/**
 * 应用配置目录
 * @param string $path
 * @return string
 */
function config_path($path = '')
{
    return base_path('config' . ($path ? DIRECTORY_SEPARATOR . $path : $path));
}

/**
 * 应用数据库目录
 * @param string $path
 * @return string
 */
function database_path($path = '')
{
    return base_path('database' . ($path ? DIRECTORY_SEPARATOR . $path : $path));
}

/**
 * 入口文件目录
 * @param string $path
 * @return string
 */
function public_path($path = '')
{
    return base_path('public' . ($path ? DIRECTORY_SEPARATOR . $path : $path));
}

/**
 * 资源文件目录
 * @param string $path
 * @return string
 */
function resource_path($path = '')
{
    return base_path('resources' . ($path ? DIRECTORY_SEPARATOR . $path : $path));
}

/**
 * 文件存储目录
 * @param string $path
 * @return string
 */
function storage_path($path = '')
{
    return base_path('storage' . ($path ? DIRECTORY_SEPARATOR . $path : $path));
}

/**
 * 插件目录
 * @param string $path
 * @return string
 */
function plugin_path($path = '')
{
    return app_path('plugins' . ($path ? DIRECTORY_SEPARATOR . $path : $path));
}

/**
 * 获取应用主体
 * @param null $component
 * @return bool|mixed
 */
function app($component = null)
{
    if (!is_null($component) && isset(Yii::$app->{$component})) {
        return Yii::$app->{$component};
    }

    return false;
}

/**
 * Get / set the specified configuration value.
 *
 * If an array is passed as the key, we will assume you want to set an array of values.
 *
 * @param  array|string $key
 * @return mixed
 */
function config($key = null)
{
    static $_config = [];
    $item = 'params';
    // 指定参数来源
    if (strpos($key, '.')) {
        list($item, $key) = explode('.', $key, 2);
    }
    if (!isset($_config[$item])) {
        $_config[$item] = require config_path($item . '.php');
    }
    return isset($_config[$item][$key]) ? $_config[$item][$key] : null;
}

/**
 * 缓存管理
 * @param mixed $name 缓存名称，如果为数组表示进行缓存设置
 * @param mixed $value 缓存值
 * @param mixed $expire 缓存时间
 * @return mixed
 */
function cache($name, $value = '', $expire = 3600)
{
    $cache = app('cache');
    if ('' === $value) {
        // 获取缓存
        return 0 === strpos($name, '?') ? $cache->exists(substr($name, 1)) : $cache->get($name);
    } elseif (is_null($value)) {
        // 删除缓存
        return $cache->delete($name);
    } else {
        // 缓存数据
        return $cache->set($name, $value, $expire);
    }
}

/**
 * Get / set the specified session value.
 * @param $name
 * @param string $value
 * @return mixed
 */
function session($name, $value = '')
{
    if (is_null($name)) {
        // 清除
        app('session')->destroy();
    } elseif ('' === $value) {
        // 判断或获取
        return 0 === strpos($name, '?') ? app('session')->has(substr($name, 1)) : app('session')->get($name);
    } elseif (is_null($value)) {
        // 删除
        return app('session')->remove($name);
    } else {
        // 设置
        return app('session')->set($name, $value);
    }
}

/**
 * Cookie管理
 * @param string|array $name cookie名称，如果为数组表示进行cookie设置
 * @param mixed $value cookie值
 * @return mixed
 */
function cookie($name, $value = '', $minutes = 0, $path = null, $domain = null, $secure = false, $httpOnly = true)
{
    if (is_null($name)) {
        // 清除
        app('response')->cookies->removeAll();
    } elseif ('' === $value) {
        // 获取
        $cookie = app('request')->cookies;
        return 0 === strpos($name, '?') ? $cookie->has(substr($name, 1)) : $cookie->getValue($name);
    } elseif (is_null($value)) {
        // 删除
        return app('response')->cookies->remove($name);
    } else {
        // 设置
        return app('response')->cookies->add(new \yii\web\Cookie([
            'name' => $name,
            'value' => $value,
            'expire' => $minutes,
            'path' => $path,
            'domain' => $domain,
            'secure' => $secure,
            'httpOnly' => $httpOnly
        ]));
    }
}

/**
 * @return string
 */
function csrf_field()
{
    $request = app('request');
    return '<input type="hidden" name="' . $request->csrfParam . '" value="' . $request->getCsrfToken() . '" />';
}

/**
 * @return array
 */
function input($name = '', $default = null)
{
    return Yii::$app->request->get($name, $default);
}

/**
 * @param $url
 * @param array $param
 * @return string
 */
function url($url, $param = [])
{
    return yii\helpers\Url::toRoute(array_merge([$url], $param));
}

/**
 * Generate the URL to an application asset.
 *
 * @param  string $path
 * @return string
 */
function asset($path = '/')
{
    if (is_valid_url($path)) {
        return $path;
    }
    // Once we get the root URL, we will check to see if it contains an index.php
    // file in the paths. If it does, we will remove it since it is not needed
    // for asset paths, but only for routes to endpoints in the application.
    $root = yii\helpers\Url::home();
    $root = str_replace('/index.php', '', $root);

    return rtrim($root, '/') . '/' . trim($path, '/');
}

/**
 * Determine if the given path is a valid URL.
 *
 * @param  string $path
 * @return bool
 */
function is_valid_url($path)
{
    if (!preg_match('~^(#|//|https?://|mailto:|tel:)~', $path)) {
        return filter_var($path, FILTER_VALIDATE_URL) !== false;
    }
    return true;
}

/**
 * 将指定的字符串转换成 驼峰式命名
 * Translates a string with underscores
 * into camel case (e.g. first_name -> firstName)
 *
 * @param string $str String in underscore format
 * @param bool $capitalise_first_char If true, capitalise the first char in $str
 * @return string $str translated into camel caps
 */
function camel_case($str, $capitalise_first_char = false)
{
    if ($capitalise_first_char) {
        $str[0] = strtoupper($str[0]);
    }
    return preg_replace_callback('/_([a-z])/', function ($c) {
        return strtoupper($c[1]);
    }, $str);
}

/**
 * 将指定的字符串转换成 蛇形命名
 * Translates a camel case string into a string with
 * underscores (e.g. firstName -> first_name)
 *
 * @param string $str String in camel case format
 * @return string $str Translated into underscore format
 */
function snake_case($str)
{
    $str[0] = strtolower($str[0]);
    return preg_replace_callback('/([A-Z])/', function ($c) {
        return "_" . strtolower($c[1]);
    }, $str);
}

/**
 * 是否为移动设备
 * @return mixed
 */
function is_mobile_device()
{
    $detect = new \Mobile_Detect();
    return $detect->isMobile();
}

/**
 * 加载函数库
 * @param array $files
 * @param string $module
 */
function load_helper($files = [], $module = '')
{
    if (!is_array($files)) {
        $files = [$files];
    }
    if (empty($module)) {
        $base_path = app_path('helpers/');
    } else {
        $base_path = app_path($module . '/helpers/');
    }
    foreach ($files as $vo) {
        $helper = $base_path . $vo . '.php';
        if (file_exists($helper)) {
            require_once $helper;
        }
    }
}

/**
 * 加载语言包
 * @param array $files
 * @param string $module
 */
function load_lang($files = [], $module = '')
{
    static $_LANG = [];
    if (!is_array($files)) {
        $files = [$files];
    }
    if (empty($module)) {
        $base_path = resource_path('lang/' . $GLOBALS['_CFG']['lang'] . '/');
    } else {
        $base_path = app_path($module . '/languages/' . $GLOBALS['_CFG']['lang'] . '/');
    }
    foreach ($files as $vo) {
        $helper = $base_path . $vo . '.php';
        $lang = null;
        if (file_exists($helper)) {
            $lang = require_once($helper);
            if (!is_null($lang)) {
                $_LANG = array_merge($_LANG, $lang);
            }
        }
    }
    $GLOBALS['_LANG'] = $_LANG;
}

/**
 * 浏览器友好的变量输出
 * @param mixed $var 变量
 * @param boolean $echo 是否输出 默认为True 如果为false 则返回输出字符串
 * @param string $label 标签 默认为空
 * @param boolean $strict 是否严谨 默认为true
 * @return void|string
 */
function dd($var, $echo = true, $label = null, $strict = true)
{
    $label = ($label === null) ? '' : rtrim($label) . ' ';
    if (!$strict) {
        if (ini_get('html_errors')) {
            $output = print_r($var, true);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        } else {
            $output = $label . print_r($var, true);
        }
    } else {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        if (!extension_loaded('xdebug')) {
            $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        }
    }
    if ($echo) {
        die($output);
    } else {
        return $output;
    }
}
