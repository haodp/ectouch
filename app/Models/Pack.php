<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Pack
 * @package App\Models
 * @property $pack_name
 * @property $pack_img
 * @property $pack_fee
 * @property $free_money
 * @property $pack_desc
 */
class Pack extends Model
{
    protected $table = 'pack';

    protected $pk = 'pack_id';

}
