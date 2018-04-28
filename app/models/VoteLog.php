<?php

namespace app\models;

use think\Model;

/**
 * Class VoteLog
 * @package app\models
 * @property $vote_id
 * @property $ip_address
 * @property $vote_time
 */
class VoteLog extends Model
{
    protected $table = 'vote_log';

    protected $pk = 'log_id';

}
