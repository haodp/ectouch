<?php

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
 * 是否为移动设备
 *
 * @param bool $return
 * @return string
 */
function is_mobile_device($return = false)
{
    $detect = new \Mobile_Detect();
    $flag = $detect->isMobile();

    if ($return) {
        return $flag ? 'mobile' : 'pc';
    }

    return $flag;
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
        $base_path = app_path('Helpers/');
    } else {
        $base_path = app_path('Modules/' . ucfirst($module) . '/Helpers/');
    }
    foreach ($files as $vo) {
        $helper = $base_path . $vo . '.php';
        if (file_exists($helper)) {
            require_once $helper;
        }
    }
}

/**
 * 加载配置文件
 * @param array $files
 * @param string $module
 */
function load_conf($files = [], $module = '')
{
    if (!is_array($files)) {
        $files = [$files];
    }
    if (empty($module)) {
        $base_path = base_path('config/');
    } else {
        $base_path = app_path('Modules/' . ucfirst($module) . '/Config/');
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
        $base_path = app_path('Modules/' . ucfirst($module) . '/Languages/' . $GLOBALS['_CFG']['lang'] . '/');
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
