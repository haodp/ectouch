<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserAccount
 * @package App\Models
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
