<?php

namespace app\models;

use think\Model;

/**
 * Class VirtualCard
 * @package app\models
 * @property $goods_id
 * @property $card_sn
 * @property $card_password
 * @property $add_date
 * @property $end_date
 * @property $is_saled
 * @property $order_sn
 * @property $crc32
 */
class VirtualCard extends Model
{
    protected $table = 'virtual_card';

    protected $pk = 'card_id';

}
