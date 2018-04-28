<?php

namespace app\models;

use think\Model;

/**
 * Class OrderAction
 * @package app\models
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
