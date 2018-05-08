<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GoodsAttr
 * @package App\Models
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
