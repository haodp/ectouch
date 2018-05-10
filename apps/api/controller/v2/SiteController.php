<?php

namespace app\api\controller\v2;

use app\api\controller\Controller;
use app\api\model\v2\ShopConfig;

class SiteController extends Controller
{
    //POST  ecapi.site.get
    public function index()
    {
        return $this->json(ShopConfig::getSiteInfo());
    }
}
