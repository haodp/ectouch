<?php

namespace app\models;

use think\Model;

/**
 * Class BookingGoods
 * @package app\models
 * @property $user_id
 * @property $email
 * @property $link_man
 * @property $tel
 * @property $goods_id
 * @property $goods_desc
 * @property $goods_number
 * @property $booking_time
 * @property $is_dispose
 * @property $dispose_user
 * @property $dispose_time
 * @property $dispose_note
 */
class BookingGoods extends Model
{
    protected $table = 'booking_goods';

    protected $pk = 'rec_id';

}
