<?php

namespace app\api\controller\v2;

use app\api\controller\Controller;
use app\api\model\v2\Configs;

class ConfigController extends Controller
{
    public function index()
    {
        $data = Configs::getList();
        return $this->json($data);
    }
}
