<?php

namespace app\models;

use think\Model;

/**
 * Class Cart
 * @package app\models
 * @property $user_id
 * @property $session_id
 * @property $goods_id
 * @property $goods_sn
 * @property $product_id
 * @property $goods_name
 * @property $market_price
 * @property $goods_price
 * @property $goods_number
 * @property $goods_attr
 * @property $is_real
 * @property $extension_code
 * @property $parent_id
 * @property $rec_type
 * @property $is_gift
 * @property $is_shipping
 * @property $can_handsel
 * @property $goods_attr_id
 */
class Cart extends Model
{
    protected $table = 'cart';

    protected $pk = 'rec_id';

}
