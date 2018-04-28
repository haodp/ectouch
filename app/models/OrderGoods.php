<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%order_goods}}".
 *
 * @property int $rec_id
 * @property int $order_id
 * @property int $goods_id
 * @property string $goods_name
 * @property string $goods_sn
 * @property int $product_id
 * @property int $goods_number
 * @property string $market_price
 * @property string $goods_price
 * @property string $goods_attr
 * @property int $send_number
 * @property int $is_real
 * @property string $extension_code
 * @property int $parent_id
 * @property int $is_gift
 * @property string $goods_attr_id
 */
class OrderGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_goods}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'goods_id', 'product_id', 'goods_number', 'send_number', 'parent_id', 'is_gift'], 'integer'],
            [['market_price', 'goods_price'], 'number'],
            [['goods_attr'], 'required'],
            [['goods_attr'], 'string'],
            [['goods_name'], 'string', 'max' => 120],
            [['goods_sn'], 'string', 'max' => 60],
            [['is_real'], 'string', 'max' => 1],
            [['extension_code'], 'string', 'max' => 30],
            [['goods_attr_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rec_id' => 'Rec ID',
            'order_id' => 'Order ID',
            'goods_id' => 'Goods ID',
            'goods_name' => 'Goods Name',
            'goods_sn' => 'Goods Sn',
            'product_id' => 'Product ID',
            'goods_number' => 'Goods Number',
            'market_price' => 'Market Price',
            'goods_price' => 'Goods Price',
            'goods_attr' => 'Goods Attr',
            'send_number' => 'Send Number',
            'is_real' => 'Is Real',
            'extension_code' => 'Extension Code',
            'parent_id' => 'Parent ID',
            'is_gift' => 'Is Gift',
            'goods_attr_id' => 'Goods Attr ID',
        ];
    }
}
