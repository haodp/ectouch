<?php

namespace app\api\controller\v2;

use app\api\controller\Controller;
use app\api\model\v2\Cart;
use app\api\classes\Token;

class CartController extends Controller
{

    /**
     * POST ecapi.cart.add
     */
    public function add()
    {
        $rules = [
            'product'     => 'required|integer|min:1',
            'property'    => 'json',
            'amount'      => 'required|integer',
            'attachments' => 'json',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $response = Cart::add($this->validated);

        return $this->json($response);
    }

    /**
     * POST ecapi.cart.delete
     */

    public function delete()
    {
        $rules = [
            'good'     => 'required|string|min:1',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $response = Cart::remove($this->validated);

        return $this->json($response);
    }

    public function update()
    {
        $rules = [
            'good'     => 'required|string|min:1',
            'amount'     => 'required|integer|min:1',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $response = Cart::updateAmount($this->validated);

        return $this->json($response);
    }

    /**
     * POST ecapi.cart.get
     */

    public function actionIndex()
    {
        $response = Cart::getList();
        return $this->json($response);
    }


    /**
     * POST ecapi.cart.clear
     */

    public function clear()
    {
        $response = Cart::clear();
        return $this->json($response);
    }

    /**
     * 加入购物车
     * @param   [description]
     * @return [type]           [description]
     */
    public function checkout()
    {
        $rules = [
            "shop"             => "integer|min:1",          // 店铺ID
            "consignee"        => "required|integer|min:1", // 收货人ID
            "shipping"         => "required|integer|min:1", // 快递ID
            "invoice_type"     => "string|min:1",           // 发票类型ID，如：公司、个人
            "invoice_content"  => "string|min:1",           // 发票内容ID，如：办公用品、礼品
            "invoice_title"    => "string|min:1",           // 发票抬头，如：xx科技有限公司
            "coupon"           => "string|min:1",          // 优惠券ID
            "cashgift"         => "string|min:1",          // 红包ID
            "comment"          => "string|min:1",           // 留言
            "score"            => "integer",                // 积分
            "cart_good_id"     => "required|json",         // 购物车商品id数组
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $response = Cart::checkout($this->validated);
        return $this->json($response);
    }

    /**
     * 购物车商品促销信息
     * @param   [description]
     * @return [type]           [description]
     */
    public function promos()
    {
        $rules = [
            "cart_good_id"     => "required|json",         // 购物车商品id数组
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $response = Cart::promos($this->validated);

        return $this->json($response);
    }
}
