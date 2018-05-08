<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ShippingArea
 * @package App\Models
 * @property $shipping_area_name
 * @property $shipping_id
 * @property $configure
 */
class ShippingArea extends Model
{
    protected $table = 'shipping_area';

    protected $pk = 'shipping_area_id';

}
