<?php

namespace app\models;

use think\Model;

/**
 * Class ShopConfig
 * @package app\models
 * @property $parent_id
 * @property $code
 * @property $type
 * @property $store_range
 * @property $store_dir
 * @property $value
 * @property $sort_order
 */
class ShopConfig extends Model
{
    protected $table = 'shop_config';

}
