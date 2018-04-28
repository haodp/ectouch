<?php

namespace app\kernel\route\dispatch;

use app\kernel\Response;
use app\kernel\route\Dispatch;

class Redirect extends Dispatch
{
    public function run()
    {
        return Response::create($this->dispatch, 'redirect')->code($this->code);
    }
}
