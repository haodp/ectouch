<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Payment
 * @package App\Models
 * @property $pay_code
 * @property $pay_name
 * @property $pay_fee
 * @property $pay_desc
 * @property $pay_order
 * @property $pay_config
 * @property $enabled
 * @property $is_cod
 * @property $is_online
 */
class Payment extends Model
{
    protected $table = 'payment';

    protected $pk = 'pay_id';

}
