<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AffiliateLog
 * @package App\Models
 * @property $order_id
 * @property $time
 * @property $user_id
 * @property $user_name
 * @property $money
 * @property $point
 * @property $separate_type
 */
class AffiliateLog extends Model
{
    protected $table = 'affiliate_log';

    protected $pk = 'log_id';

}
