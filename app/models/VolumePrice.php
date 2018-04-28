<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%volume_price}}".
 *
 * @property int $price_type
 * @property int $goods_id
 * @property int $volume_number
 * @property string $volume_price
 */
class VolumePrice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%volume_price}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['price_type', 'goods_id', 'volume_number'], 'required'],
            [['goods_id', 'volume_number'], 'integer'],
            [['volume_price'], 'number'],
            [['price_type'], 'string', 'max' => 1],
            [['price_type', 'goods_id', 'volume_number'], 'unique', 'targetAttribute' => ['price_type', 'goods_id', 'volume_number']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'price_type' => 'Price Type',
            'goods_id' => 'Goods ID',
            'volume_number' => 'Volume Number',
            'volume_price' => 'Volume Price',
        ];
    }
}
