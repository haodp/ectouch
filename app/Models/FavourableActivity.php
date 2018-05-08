<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FavourableActivity
 * @package App\Models
 * @property $act_name
 * @property $start_time
 * @property $end_time
 * @property $user_rank
 * @property $act_range
 * @property $act_range_ext
 * @property $min_amount
 * @property $max_amount
 * @property $act_type
 * @property $act_type_ext
 * @property $gift
 * @property $sort_order
 */
class FavourableActivity extends Model
{
    protected $table = 'favourable_activity';

    protected $pk = 'act_id';

}
