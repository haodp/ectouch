<?php

namespace app\models;

use think\Model;

/**
 * Class ShippingArea
 * @package app\models
 * @property $shipping_area_name
 * @property $shipping_id
 * @property $configure
 */
class ShippingArea extends Model
{
    protected $table = 'shipping_area';

    protected $pk = 'shipping_area_id';

}
