<?php

namespace app\api\controller\v2;

use app\api\controller\Controller;
use app\api\classes\Token;
use app\api\model\v2\Keywords;

class SearchController extends Controller
{
    //POST  ecapi.search.keyword.list
    public function index()
    {
        return $this->json(Keywords::getHot());
    }
}
