<?php

namespace app\models;

use think\Model;

/**
 * Class GoodsType
 * @package app\models
 * @property $cat_name
 * @property $enabled
 * @property $attr_group
 */
class GoodsType extends Model
{
    protected $table = 'goods_type';

    protected $pk = 'cat_id';

}
