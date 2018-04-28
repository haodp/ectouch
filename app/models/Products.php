<?php

namespace app\models;

use think\Model;

/**
 * Class Products
 * @package app\models
 * @property $goods_id
 * @property $goods_attr
 * @property $product_sn
 * @property $product_number
 */
class Products extends Model
{
    protected $table = 'products';

    protected $pk = 'product_id';

}
