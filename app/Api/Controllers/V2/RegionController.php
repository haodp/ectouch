<?php

namespace App\Api\Controllers\V2;

use app\api\controller\Controller;
use app\api\model\v2\Region;

class RegionController extends Controller
{
    public function index()
    {
        $response = Region::getList();
        return $this->json($response);
    }
}
