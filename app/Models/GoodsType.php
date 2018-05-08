<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GoodsType
 * @package App\Models
 * @property $cat_name
 * @property $enabled
 * @property $attr_group
 */
class GoodsType extends Model
{
    protected $table = 'goods_type';

    protected $pk = 'cat_id';

}
