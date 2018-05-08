<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class LinkGoods
 * @package App\Models
 * @property $goods_id
 * @property $link_goods_id
 * @property $is_double
 * @property $admin_id
 */
class LinkGoods extends Model
{
    protected $table = 'link_goods';

}
