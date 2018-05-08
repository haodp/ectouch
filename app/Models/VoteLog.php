<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class VoteLog
 * @package App\Models
 * @property $vote_id
 * @property $ip_address
 * @property $vote_time
 */
class VoteLog extends Model
{
    protected $table = 'vote_log';

    protected $pk = 'log_id';

}
