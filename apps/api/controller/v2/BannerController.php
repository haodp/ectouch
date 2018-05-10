<?php

namespace app\api\controller\v2;

use app\api\controller\Controller;
use app\api\model\v2\Banner;

class BannerController extends Controller
{

    /**
    * POST ecapi.banner.list
    */
    public function index()
    {
        $model = Banner::getList();

        return $this->json($model);
    }
}
