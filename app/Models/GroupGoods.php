<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GroupGoods
 * @package App\Models
 * @property $parent_id
 * @property $goods_id
 * @property $goods_price
 * @property $admin_id
 */
class GroupGoods extends Model
{
    protected $table = 'group_goods';

}
