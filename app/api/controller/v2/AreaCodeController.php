<?php

namespace app\api\controller\v2;

use app\api\controller\Controller;
use app\api\model\v2\AreaCode;

class AreaCodeController extends Controller
{
    /**
    * POST ecapi.areacode.list
    */
    public function index()
    {
        $model = AreaCode::getList();

        return $this->json($model);
    }
}
