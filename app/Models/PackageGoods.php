<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PackageGoods
 * @package App\Models
 * @property $package_id
 * @property $goods_id
 * @property $product_id
 * @property $goods_number
 * @property $admin_id
 */
class PackageGoods extends Model
{
    protected $table = 'package_goods';

}
