<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%goods}}".
 *
 * @property int $goods_id
 * @property int $cat_id
 * @property string $goods_sn
 * @property string $goods_name
 * @property string $goods_name_style
 * @property int $click_count
 * @property int $brand_id
 * @property string $provider_name
 * @property int $goods_number
 * @property string $goods_weight
 * @property string $market_price
 * @property string $shop_price
 * @property string $promote_price
 * @property int $promote_start_date
 * @property int $promote_end_date
 * @property int $warn_number
 * @property string $keywords
 * @property string $goods_brief
 * @property string $goods_desc
 * @property string $goods_thumb
 * @property string $goods_img
 * @property string $original_img
 * @property int $is_real
 * @property string $extension_code
 * @property int $is_on_sale
 * @property int $is_alone_sale
 * @property int $is_shipping
 * @property int $integral
 * @property int $add_time
 * @property int $sort_order
 * @property int $is_delete
 * @property int $is_best
 * @property int $is_new
 * @property int $is_hot
 * @property int $is_promote
 * @property int $bonus_type_id
 * @property int $last_update
 * @property int $goods_type
 * @property string $seller_note
 * @property int $give_integral
 * @property int $rank_integral
 * @property int $suppliers_id
 * @property int $is_check
 */
class Goods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cat_id', 'click_count', 'brand_id', 'goods_number', 'promote_start_date', 'promote_end_date', 'integral', 'add_time', 'sort_order', 'last_update', 'goods_type', 'give_integral', 'rank_integral', 'suppliers_id'], 'integer'],
            [['goods_weight', 'market_price', 'shop_price', 'promote_price'], 'number'],
            [['goods_desc'], 'required'],
            [['goods_desc'], 'string'],
            [['goods_sn', 'goods_name_style'], 'string', 'max' => 60],
            [['goods_name'], 'string', 'max' => 120],
            [['provider_name'], 'string', 'max' => 100],
            [['warn_number', 'is_real', 'bonus_type_id'], 'string', 'max' => 3],
            [['keywords', 'goods_brief', 'goods_thumb', 'goods_img', 'original_img', 'seller_note'], 'string', 'max' => 255],
            [['extension_code'], 'string', 'max' => 30],
            [['is_on_sale', 'is_alone_sale', 'is_shipping', 'is_delete', 'is_best', 'is_new', 'is_hot', 'is_promote', 'is_check'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'goods_id' => 'Goods ID',
            'cat_id' => 'Cat ID',
            'goods_sn' => 'Goods Sn',
            'goods_name' => 'Goods Name',
            'goods_name_style' => 'Goods Name Style',
            'click_count' => 'Click Count',
            'brand_id' => 'Brand ID',
            'provider_name' => 'Provider Name',
            'goods_number' => 'Goods Number',
            'goods_weight' => 'Goods Weight',
            'market_price' => 'Market Price',
            'shop_price' => 'Shop Price',
            'promote_price' => 'Promote Price',
            'promote_start_date' => 'Promote Start Date',
            'promote_end_date' => 'Promote End Date',
            'warn_number' => 'Warn Number',
            'keywords' => 'Keywords',
            'goods_brief' => 'Goods Brief',
            'goods_desc' => 'Goods Desc',
            'goods_thumb' => 'Goods Thumb',
            'goods_img' => 'Goods Img',
            'original_img' => 'Original Img',
            'is_real' => 'Is Real',
            'extension_code' => 'Extension Code',
            'is_on_sale' => 'Is On Sale',
            'is_alone_sale' => 'Is Alone Sale',
            'is_shipping' => 'Is Shipping',
            'integral' => 'Integral',
            'add_time' => 'Add Time',
            'sort_order' => 'Sort Order',
            'is_delete' => 'Is Delete',
            'is_best' => 'Is Best',
            'is_new' => 'Is New',
            'is_hot' => 'Is Hot',
            'is_promote' => 'Is Promote',
            'bonus_type_id' => 'Bonus Type ID',
            'last_update' => 'Last Update',
            'goods_type' => 'Goods Type',
            'seller_note' => 'Seller Note',
            'give_integral' => 'Give Integral',
            'rank_integral' => 'Rank Integral',
            'suppliers_id' => 'Suppliers ID',
            'is_check' => 'Is Check',
        ];
    }
}
