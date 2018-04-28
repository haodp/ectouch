<?php

namespace app\kernel\route\dispatch;

use app\kernel\route\Dispatch;

class Response extends Dispatch
{
    public function run()
    {
        return $this->dispatch;
    }

}
