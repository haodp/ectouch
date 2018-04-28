<?php

namespace app\kernel\route\dispatch;

use app\kernel\Response;
use app\kernel\route\Dispatch;

class View extends Dispatch
{
    public function run()
    {
        // 渲染模板输出
        $vars = array_merge($this->app['request']->param(), $this->param);

        return Response::create($this->dispatch, 'view')->assign($vars);
    }
}
