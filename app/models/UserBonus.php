<?php

namespace app\models;

use think\Model;

/**
 * Class UserBonus
 * @package app\models
 * @property $bonus_type_id
 * @property $bonus_sn
 * @property $user_id
 * @property $used_time
 * @property $order_id
 * @property $emailed
 */
class UserBonus extends Model
{
    protected $table = 'user_bonus';

    protected $pk = 'bonus_id';

}
