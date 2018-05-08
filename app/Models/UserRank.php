<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserRank
 * @package App\Models
 * @property $rank_name
 * @property $min_points
 * @property $max_points
 * @property $discount
 * @property $show_price
 * @property $special_rank
 */
class UserRank extends Model
{
    protected $table = 'user_rank';

    protected $pk = 'rank_id';

}
