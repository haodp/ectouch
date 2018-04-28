<?php

namespace app\kernel;

use app\kernel\console\Output as ConsoleOutput;
use app\kernel\exception\ErrorException;
use app\kernel\exception\Handle;
use app\kernel\exception\ThrowableError;

class Error
{
    /**
     * 注册异常处理
     * @access public
     * @return void
     */
    public static function register()
    {
        error_reporting(E_ALL);
        set_error_handler([__CLASS__, 'appError']);
        set_exception_handler([__CLASS__, 'appException']);
        register_shutdown_function([__CLASS__, 'appShutdown']);
    }

    /**
     * Exception Handler
     * @access public
     * @param  \Exception|\Throwable $e
     */
    public static function appException($e)
    {
        if (!$e instanceof \Exception) {
            $e = new ThrowableError($e);
        }

        self::getExceptionHandler()->report($e);

        if (PHP_SAPI == 'cli') {
            self::getExceptionHandler()->renderForConsole(new ConsoleOutput, $e);
        } else {
            self::getExceptionHandler()->render($e)->send();
        }
    }

    /**
     * Error Handler
     * @access public
     * @param  integer $errno   错误编号
     * @param  integer $errstr  详细错误信息
     * @param  string  $errfile 出错的文件
     * @param  integer $errline 出错行号
     * @throws ErrorException
     */
    public static function appError($errno, $errstr, $errfile = '', $errline = 0)
    {
        $exception = new ErrorException($errno, $errstr, $errfile, $errline);
        if (error_reporting() & $errno) {
            // 将错误信息托管至 app\kernel\exception\ErrorException
            throw $exception;
        }

        self::getExceptionHandler()->report($exception);
    }

    /**
     * Shutdown Handler
     * @access public
     */
    public static function appShutdown()
    {
        if (!is_null($error = error_get_last()) && self::isFatal($error['type'])) {
            // 将错误信息托管至app\kernel\ErrorException
            $exception = new ErrorException($error['type'], $error['message'], $error['file'], $error['line']);

            self::appException($exception);
        }

        // 写入日志
        Container::get('log')->save();
    }

    /**
     * 确定错误类型是否致命
     *
     * @access protected
     * @param  int $type
     * @return bool
     */
    protected static function isFatal($type)
    {
        return in_array($type, [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE]);
    }

    /**
     * Get an instance of the exception handler.
     *
     * @access public
     * @return Handle
     */
    public static function getExceptionHandler()
    {
        static $handle;

        if (!$handle) {
            // 异常处理handle
            $class = Container::get('config')->get('exception_handle');
            if ($class && is_string($class) && class_exists($class) && is_subclass_of($class, "\\app\\kernel\\exception\\Handle")) {
                $handle = new $class;
            } else {
                $handle = new Handle;
                if ($class instanceof \Closure) {
                    $handle->setRender($class);
                }
            }
        }

        return $handle;
    }
}
