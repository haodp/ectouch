<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ShopConfig
 * @package App\Models
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
