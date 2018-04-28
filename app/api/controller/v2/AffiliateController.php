<?php

namespace app\api\controller\v2;

use app\api\controller\Controller;
use app\api\model\v2\AffiliateLog;

class AffiliateController extends Controller
{

    /**
    * POST ecapi.recommend.affiliate.list
    */
    public function actionIndex()
    {
        $rules = [
            'page' => 'required|integer|min:1',
            'per_page' => 'required|integer|min:1',
        ];
        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $model = AffiliateLog::getList($this->validated);
        return $this->json($model);
    }

    /**
    * POST ecapi.recommend.affiliate.info
    */
    public function info()
    {
        $data = AffiliateLog::info();
        return $this->json($data);
    }
}
