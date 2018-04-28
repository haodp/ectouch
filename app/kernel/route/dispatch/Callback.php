<?php

namespace app\kernel\route\dispatch;

use app\kernel\Container;
use app\kernel\route\Dispatch;

class Callback extends Dispatch
{
    public function run()
    {
        // 执行回调方法
        $vars = array_merge($this->app['request']->param(), $this->param);

        return Container::getInstance()->invoke($this->dispatch, $vars);
    }

}
