<?php

namespace app\models;

use think\Model;

/**
 * Class Role
 * @package app\models
 * @property $role_name
 * @property $action_list
 * @property $role_describe
 */
class Role extends Model
{
    protected $table = 'role';

    protected $pk = 'role_id';

}
