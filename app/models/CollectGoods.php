<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%collect_goods}}".
 *
 * @property int $rec_id
 * @property int $user_id
 * @property int $goods_id
 * @property int $add_time
 * @property int $is_attention
 */
class CollectGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%collect_goods}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'goods_id', 'add_time'], 'integer'],
            [['is_attention'], 'string', 'max' => 1],
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
            'goods_id' => 'Goods ID',
            'add_time' => 'Add Time',
            'is_attention' => 'Is Attention',
        ];
    }
}
