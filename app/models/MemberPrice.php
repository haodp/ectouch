<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%member_price}}".
 *
 * @property int $price_id
 * @property int $goods_id
 * @property int $user_rank
 * @property string $user_price
 */
class MemberPrice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_price}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id'], 'integer'],
            [['user_price'], 'number'],
            [['user_rank'], 'string', 'max' => 3],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'price_id' => 'Price ID',
            'goods_id' => 'Goods ID',
            'user_rank' => 'User Rank',
            'user_price' => 'User Price',
        ];
    }
}
