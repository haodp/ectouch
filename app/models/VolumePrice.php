<?php

namespace app\models;

use think\Model;

/**
 * Class VolumePrice
 * @package app\models
 * @property $price_type
 * @property $goods_id
 * @property $volume_number
 * @property $volume_price
 */
class VolumePrice extends Model
{
    protected $table = 'volume_price';

}
