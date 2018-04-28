<?php

namespace app\models;

use think\Model;

/**
 * Class OrderGoods
 * @package app\models
 * @property $order_id
 * @property $goods_id
 * @property $goods_name
 * @property $goods_sn
 * @property $product_id
 * @property $goods_number
 * @property $market_price
 * @property $goods_price
 * @property $goods_attr
 * @property $send_number
 * @property $is_real
 * @property $extension_code
 * @property $parent_id
 * @property $is_gift
 * @property $goods_attr_id
 */
class OrderGoods extends Model
{
    protected $table = 'order_goods';

    protected $pk = 'rec_id';

}
