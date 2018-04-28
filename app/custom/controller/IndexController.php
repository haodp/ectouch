<?php

namespace app\custom\controller;

use app\http\controller\IndexController as BaseController;

class IndexController extends BaseController
{
    public function actionIndex()
    {
        return 'Hello Developer.';
    }
}
