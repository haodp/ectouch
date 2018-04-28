<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%cart}}".
 *
 * @property int $rec_id
 * @property int $user_id
 * @property string $session_id
 * @property int $goods_id
 * @property string $goods_sn
 * @property int $product_id
 * @property string $goods_name
 * @property string $market_price
 * @property string $goods_price
 * @property int $goods_number
 * @property string $goods_attr
 * @property int $is_real
 * @property string $extension_code
 * @property int $parent_id
 * @property int $rec_type
 * @property int $is_gift
 * @property int $is_shipping
 * @property int $can_handsel
 * @property string $goods_attr_id
 */
class Cart extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cart}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'goods_id', 'product_id', 'goods_number', 'parent_id', 'is_gift'], 'integer'],
            [['market_price', 'goods_price'], 'number'],
            [['goods_attr'], 'required'],
            [['goods_attr'], 'string'],
            [['session_id'], 'string', 'max' => 32],
            [['goods_sn'], 'string', 'max' => 60],
            [['goods_name'], 'string', 'max' => 120],
            [['is_real', 'rec_type', 'is_shipping'], 'string', 'max' => 1],
            [['extension_code'], 'string', 'max' => 30],
            [['can_handsel'], 'string', 'max' => 3],
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
            'user_id' => 'User ID',
            'session_id' => 'Session ID',
            'goods_id' => 'Goods ID',
            'goods_sn' => 'Goods Sn',
            'product_id' => 'Product ID',
            'goods_name' => 'Goods Name',
            'market_price' => 'Market Price',
            'goods_price' => 'Goods Price',
            'goods_number' => 'Goods Number',
            'goods_attr' => 'Goods Attr',
            'is_real' => 'Is Real',
            'extension_code' => 'Extension Code',
            'parent_id' => 'Parent ID',
            'rec_type' => 'Rec Type',
            'is_gift' => 'Is Gift',
            'is_shipping' => 'Is Shipping',
            'can_handsel' => 'Can Handsel',
            'goods_attr_id' => 'Goods Attr ID',
        ];
    }
}
