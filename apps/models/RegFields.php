<?php

namespace app\models;

use dao\Model;

/**
 * Class RegFields
 * @package app\models
 * @property $reg_field_name
 * @property $dis_order
 * @property $display
 * @property $type
 * @property $is_need
 */
class RegFields extends Model
{
    protected $table = 'reg_fields';

}
