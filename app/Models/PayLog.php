<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PayLog
 * @package App\Models
 * @property $order_id
 * @property $order_amount
 * @property $order_type
 * @property $is_paid
 */
class PayLog extends Model
{
    protected $table = 'pay_log';

    protected $pk = 'log_id';

}
