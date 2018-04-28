<?php

namespace app\api\controller\v2;

use app\api\controller\Controller;
use app\api\model\v2\Brand;

class BrandController extends Controller
{

    /**
    * POST ecapi.brand.list
    */
    public function index()
    {
        $rules = [
            'page'      => 'required|integer|min:1',
            'per_page'  => 'required|integer|min:1',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $model = Brand::getList($this->validated);

        return $this->json($model);
    }

    /**
    * POST ecapi.recommend.brand.list
    */
    public function recommend()
    {
        $rules = [
            'page'      => 'required|integer|min:1',
            'per_page'  => 'required|integer|min:1',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $model = Brand::getListByOrder($this->validated);

        return $this->json($model);
    }
}
