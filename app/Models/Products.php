<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Products
 * @package App\Models
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
