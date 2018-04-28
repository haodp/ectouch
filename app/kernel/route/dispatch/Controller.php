<?php

namespace app\kernel\route\dispatch;

use app\kernel\route\Dispatch;

class Controller extends Dispatch
{
    public function run()
    {
        // 执行控制器的操作方法
        $vars = array_merge($this->app['request']->param(), $this->param);

        return $this->app->action(
            $this->dispatch, $vars,
            $this->app->config('app.url_controller_layer'),
            $this->app->config('app.controller_suffix')
        );
    }

}
