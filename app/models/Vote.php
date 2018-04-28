<?php

namespace app\models;

use think\Model;

/**
 * Class Vote
 * @package app\models
 * @property $vote_name
 * @property $start_time
 * @property $end_time
 * @property $can_multi
 * @property $vote_count
 */
class Vote extends Model
{
    protected $table = 'vote';

    protected $pk = 'vote_id';

}
