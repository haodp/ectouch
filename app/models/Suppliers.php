<?php

namespace app\models;

use think\Model;

/**
 * Class Suppliers
 * @package app\models
 * @property $suppliers_name
 * @property $suppliers_desc
 * @property $is_check
 */
class Suppliers extends Model
{
    protected $table = 'suppliers';

    protected $pk = 'suppliers_id';

}
