<?php

namespace app\models;

use think\Model;

/**
 * Class ExchangeGoods
 * @package app\models
 * @property $exchange_integral
 * @property $is_exchange
 * @property $is_hot
 */
class ExchangeGoods extends Model
{
    protected $table = 'exchange_goods';

    protected $pk = 'goods_id';

}
