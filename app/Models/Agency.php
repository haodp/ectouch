<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Agency
 * @package App\Models
 * @property $agency_name
 * @property $agency_desc
 */
class Agency extends Model
{
    protected $table = 'agency';

    protected $pk = 'agency_id';

}
