<?php

namespace app\models;

use think\Model;

/**
 * Class CollectGoods
 * @package app\models
 * @property $user_id
 * @property $goods_id
 * @property $add_time
 * @property $is_attention
 */
class CollectGoods extends Model
{
    protected $table = 'collect_goods';

    protected $pk = 'rec_id';

}
