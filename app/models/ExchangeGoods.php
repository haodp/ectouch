<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%exchange_goods}}".
 *
 * @property int $goods_id
 * @property int $exchange_integral
 * @property int $is_exchange
 * @property int $is_hot
 */
class ExchangeGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%exchange_goods}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id'], 'required'],
            [['goods_id', 'exchange_integral'], 'integer'],
            [['is_exchange', 'is_hot'], 'string', 'max' => 1],
            [['goods_id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'goods_id' => 'Goods ID',
            'exchange_integral' => 'Exchange Integral',
            'is_exchange' => 'Is Exchange',
            'is_hot' => 'Is Hot',
        ];
    }
}
