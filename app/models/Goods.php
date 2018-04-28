<?php

namespace app\models;

use think\Model;

/**
 * Class Goods
 * @package app\models
 * @property $cat_id
 * @property $goods_sn
 * @property $goods_name
 * @property $goods_name_style
 * @property $click_count
 * @property $brand_id
 * @property $provider_name
 * @property $goods_number
 * @property $goods_weight
 * @property $market_price
 * @property $shop_price
 * @property $promote_price
 * @property $promote_start_date
 * @property $promote_end_date
 * @property $warn_number
 * @property $keywords
 * @property $goods_brief
 * @property $goods_desc
 * @property $goods_thumb
 * @property $goods_img
 * @property $original_img
 * @property $is_real
 * @property $extension_code
 * @property $is_on_sale
 * @property $is_alone_sale
 * @property $is_shipping
 * @property $integral
 * @property $add_time
 * @property $sort_order
 * @property $is_delete
 * @property $is_best
 * @property $is_new
 * @property $is_hot
 * @property $is_promote
 * @property $bonus_type_id
 * @property $last_update
 * @property $goods_type
 * @property $'seller_note
 * @property $give_integral
 * @property $rank_integral
 * @property $suppliers_id
 * @property $is_check
 */
class Goods extends Model
{
    protected $table = 'goods';

    protected $pk = 'goods_id';

}
