<?php

namespace app\custom\controller;

use app\shop\controller\IndexController as BaseController;

class IndexController extends BaseController
{
    public function index()
    {
        return 'Hello Developer.';
    }
}
