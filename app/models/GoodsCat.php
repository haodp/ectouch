<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%goods_cat}}".
 *
 * @property int $goods_id
 * @property int $cat_id
 */
class GoodsCat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods_cat}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'cat_id'], 'required'],
            [['goods_id', 'cat_id'], 'integer'],
            [['goods_id', 'cat_id'], 'unique', 'targetAttribute' => ['goods_id', 'cat_id']],
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
        ];
    }
}
