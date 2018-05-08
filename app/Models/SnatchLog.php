<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SnatchLog
 * @package App\Models
 * @property $snatch_id
 * @property $user_id
 * @property $bid_price
 * @property $bid_time
 *
 */
class SnatchLog extends Model
{
    protected $table = 'snatch_log';

    protected $pk = 'log_id';

}
