<?php

namespace app\models;

use think\Model;

/**
 * Class UserRank
 * @package app\models
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
