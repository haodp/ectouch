<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BookingGoods
 * @package App\Models
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
