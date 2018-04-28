<?php

namespace app\models;

use think\Model;

/**
 * Class DeliveryGoods
 * @package app\models
 * @property $delivery_id
 * @property $goods_id
 * @property $product_id
 * @property $product_sn
 * @property $goods_name
 * @property $brand_name
 * @property $goods_sn
 * @property $is_real
 * @property $extension_code
 * @property $parent_id
 * @property $send_number
 * @property $goods_attr
 */
class DeliveryGoods extends Model
{
    protected $table = 'delivery_goods';

    protected $pk = 'rec_id';

}
