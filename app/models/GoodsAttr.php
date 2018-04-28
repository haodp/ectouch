<?php

namespace app\models;

use think\Model;

/**
 * Class GoodsAttr
 * @package app\models
 * @property $goods_id
 * @property $attr_id
 * @property $attr_value
 * @property $attr_price
 */
class GoodsAttr extends Model
{
    protected $table = 'goods_attr';

    protected $pk = 'goods_attr_id';

}
