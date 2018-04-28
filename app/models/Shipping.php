<?php

namespace app\models;

use think\Model;

/**
 * Class Shipping
 * @package app\models
 * @property $shipping_code
 * @property $shipping_name
 * @property $shipping_desc
 * @property $insure
 * @property $support_cod
 * @property $enabled
 * @property $shipping_print
 * @property $print_bg
 * @property $config_lable
 * @property $print_model
 * @property $shipping_order
 */
class Shipping extends Model
{
    protected $table = 'shipping';

    protected $pk = 'shipping_id';

}
