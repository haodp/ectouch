<?php

namespace app\models;

use think\Model;

/**
 * Class BackGoods
 * @package app\models
 * @property $back_id
 * @property $goods_id
 * @property $product_id
 * @property $product_sn
 * @property $goods_name
 * @property $brand_name
 * @property $goods_sn
 * @property $is_real
 * @property $send_number
 * @property $goods_attr
 */
class BackGoods extends Model
{
    protected $table = 'back_goods';

    protected $pk = 'rec_id';

}
