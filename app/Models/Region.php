<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Region
 * @package App\Models
 * @property $parent_id
 * @property $region_name
 * @property $region_type
 * @property $agency_id
 */
class Region extends Model
{
    protected $table = 'region';

    protected $pk = 'region_id';

}
