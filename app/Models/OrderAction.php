<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class OrderAction
 * @package App\Models
 * @property $order_id
 * @property $action_user
 * @property $order_status
 * @property $shipping_status
 * @property $pay_status
 * @property $action_place
 * @property $action_note
 * @property $log_time
 */
class OrderAction extends Model
{
    protected $table = 'order_action';

    protected $pk = 'action_id';

}
