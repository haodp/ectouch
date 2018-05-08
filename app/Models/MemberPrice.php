<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MemberPrice
 * @package App\Models
 * @property $goods_id
 * @property $user_rank
 * @property $user_price
 */
class MemberPrice extends Model
{
    protected $table = 'member_price';

    protected $pk = 'price_id';

}
