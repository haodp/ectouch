<?php

namespace app\api\controller\v2;

use app\api\controller\Controller;
use app\api\model\v2\Region;

class RegionController extends Controller
{
    public function actionIndex()
    {
        $response = Region::getList();
        return $this->json($response);
    }
}
