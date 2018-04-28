<?php

namespace app\custom\controller;

use app\shop\controller\IndexController as BaseController;

class IndexController extends BaseController
{
    public function actionIndex()
    {
        return 'Hello Developer.';
    }
}
