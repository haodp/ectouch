<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ExchangeGoods
 * @package App\Models
 * @property $exchange_integral
 * @property $is_exchange
 * @property $is_hot
 */
class ExchangeGoods extends Model
{
    protected $table = 'exchange_goods';

    protected $pk = 'goods_id';

}
