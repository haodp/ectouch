<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CollectGoods
 * @package App\Models
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
