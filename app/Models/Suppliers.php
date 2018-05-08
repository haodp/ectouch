<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Suppliers
 * @package App\Models
 * @property $suppliers_name
 * @property $suppliers_desc
 * @property $is_check
 */
class Suppliers extends Model
{
    protected $table = 'suppliers';

    protected $pk = 'suppliers_id';

}
