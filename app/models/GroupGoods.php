<?php

namespace app\models;

use think\Model;

/**
 * Class GroupGoods
 * @package app\models
 * @property $parent_id
 * @property $goods_id
 * @property $goods_price
 * @property $admin_id
 */
class GroupGoods extends Model
{
    protected $table = 'group_goods';

}
