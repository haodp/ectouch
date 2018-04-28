<?php

namespace app\models;

use think\Model;

/**
 * Class PayLog
 * @package app\models
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
