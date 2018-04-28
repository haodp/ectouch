<?php

namespace app\api\controller\v2;

use app\api\controller\Controller;
use app\api\model\v2\Features;
use app\api\model\v2\Coupon;

class CouponController extends Controller
{
    //POST  ecapi.coupon.list
    public function actionIndex()
    {
        $rules = [
            'page'          => 'required|integer|min:1',
            'per_page'      => 'required|integer|min:1',
            'status'        => 'required|integer',
            'total_price'   => 'integer',
            'total_amount'  => 'integer',
            'goods'         => 'json',
        ];

        if ($res = Features::check('coupon')) {
            return $this->json($res);
        }

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $model = Coupon::getList($this->validated);

        return $this->json($model);
    }

    //POST  ecapi.coupon.available
    public function available()
    {
        $rules = [
            'page'          => 'required|integer|min:1',
            'per_page'      => 'required|integer|min:1',
            'total_price'   => 'required',
            'shop'          => 'integer',
        ];

        if ($res = Features::check('coupon')) {
            return $this->json($res);
        }

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $model = Coupon::getAvailable($this->validated);

        return $this->json($model);
    }
}
