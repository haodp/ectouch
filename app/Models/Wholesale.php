<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Wholesale
 * @package App\Models
 * @property $goods_id
 * @property $goods_name
 * @property $rank_ids
 * @property $prices
 * @property $enabled
 */
class Wholesale extends Model
{
    protected $table = 'wholesale';

    protected $pk = 'act_id';

}
