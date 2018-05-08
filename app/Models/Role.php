<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Role
 * @package App\Models
 * @property $role_name
 * @property $action_list
 * @property $role_describe
 */
class Role extends Model
{
    protected $table = 'role';

    protected $pk = 'role_id';

}
