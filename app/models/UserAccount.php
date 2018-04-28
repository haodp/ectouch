<?php

namespace app\models;

use think\Model;

/**
 * Class UserAccount
 * @package app\models
 * @property $user_id
 * @property $admin_user
 * @property $amount
 * @property $add_time
 * @property $paid_time
 * @property $admin_note
 * @property $user_note
 * @property $process_type
 * @property $payment
 * @property $is_paid
 */
class UserAccount extends Model
{
    protected $table = 'user_account';

}
