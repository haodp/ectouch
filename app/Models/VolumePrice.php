<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class VolumePrice
 * @package App\Models
 * @property $price_type
 * @property $goods_id
 * @property $volume_number
 * @property $volume_price
 */
class VolumePrice extends Model
{
    protected $table = 'volume_price';

}
