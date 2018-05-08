<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AdminUser
 * @package App\Models
 * @property $user_name
 * @property $email
 * @property $password
 * @property $ec_salt
 * @property $add_time
 * @property $last_login
 * @property $last_ip
 * @property $action_list
 * @property $nav_list
 * @property $lang_type
 * @property $agency_id
 * @property $suppliers_id
 * @property $todolist
 * @property $role_id
 */
class AdminUser extends Model
{
    protected $table = 'admin_user';

    protected $pk = 'user_id';

}