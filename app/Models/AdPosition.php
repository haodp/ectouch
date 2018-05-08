<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AdPosition
 * @package App\Models
 * @property $position_name
 * @property $ad_width
 * @property $ad_height
 * @property $position_desc
 * @property $position_style
 */
class AdPosition extends Model
{
    protected $table = 'ad_position';

    protected $pk = 'position_id';

}
