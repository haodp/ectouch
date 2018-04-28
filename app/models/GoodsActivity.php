<?php

namespace app\models;

use think\Model;

/**
 * Class GoodsActivity
 * @package app\models
 * @property $act_name
 * @property $act_desc
 * @property $act_type
 * @property $goods_id
 * @property $product_id
 * @property $goods_name
 * @property $start_time
 * @property $end_time
 * @property $is_finished
 * @property $ext_info
 */
class GoodsActivity extends Model
{
    protected $table = 'goods_activity';

    protected $pk = 'act_id';

}
