<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BonusType
 * @package App\Models
 * @property $type_name
 * @property $type_money
 * @property $send_type
 * @property $min_amount
 * @property $max_amount
 * @property $send_start_date
 * @property $send_end_date
 * @property $use_start_date
 * @property $use_end_date
 * @property $min_goods_amount
 */
class BonusType extends Model
{
    protected $table = 'bonus_type';

    protected $pk = 'type_id';

}
