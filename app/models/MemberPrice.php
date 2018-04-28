<?php

namespace app\models;

use think\Model;

/**
 * Class MemberPrice
 * @package app\models
 * @property $goods_id
 * @property $user_rank
 * @property $user_price
 */
class MemberPrice extends Model
{
    protected $table = 'member_price';

    protected $pk = 'price_id';

}
