<?php

namespace app\models;

use think\Model;

/**
 * Class Wholesale
 * @package app\models
 * @property $goods_id
 * @property $goods_name
 * @property $rank_ids
 * @property $prices
 * @property $enabled
 */
class Wholesale extends Model
{
    protected $table = 'wholesale';

    protected $pk = 'act_id';

}
