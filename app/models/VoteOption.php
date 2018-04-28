<?php

namespace app\models;

use think\Model;

/**
 * Class VoteOption
 * @package app\models
 * @property $vote_id
 * @property $option_name
 * @property $option_count
 * @property $option_order
 */
class VoteOption extends Model
{
    protected $table = 'vote_option';

    protected $pk = 'option_id';

}
