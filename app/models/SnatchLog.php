<?php

namespace app\models;

use think\Model;

/**
 * Class SnatchLog
 * @package app\models
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
