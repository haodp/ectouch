<?php

namespace app\api\controller\v2;

use app\api\controller\Controller;
use app\api\model\v2\BonusType;
use app\api\model\v2\Features;
use app\api\classes\Token;

class CashGiftController extends Controller
{

    /**
    * POST ecapi.cashgift.list
    */
    public function index()
    {
        $rules = [
            'page'      => 'required|integer|min:1',
            'per_page'  => 'required|integer|min:1',
            'status'    => 'required|integer',
        ];

        if ($res = Features::check('cashgift')) {
            return $this->json($res);
        }

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $model = BonusType::getListByUser($this->validated);

        return $this->json($model);
    }

    /**
    * POST ecapi.cashgift.available
    */
    public function available()
    {
        $rules = [
            'page'          => 'required|integer|min:1',
            'per_page'      => 'required|integer|min:1',
            'total_price'   => 'required|numeric|min:0',
        ];

        if ($res = Features::check('cashgift')) {
            return $this->json($res);
        }

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $model = BonusType::getAvailableListByUser($this->validated);

        return $this->json($model);
    }
}
