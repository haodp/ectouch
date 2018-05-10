<?php

namespace app\models;

use dao\Model;

/**
 * Class PackageGoods
 * @package app\models
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
