<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%link_goods}}".
 *
 * @property int $goods_id
 * @property int $link_goods_id
 * @property int $is_double
 * @property int $admin_id
 */
class LinkGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%link_goods}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'link_goods_id', 'admin_id'], 'required'],
            [['goods_id', 'link_goods_id'], 'integer'],
            [['is_double'], 'string', 'max' => 1],
            [['admin_id'], 'string', 'max' => 3],
            [['goods_id', 'link_goods_id', 'admin_id'], 'unique', 'targetAttribute' => ['goods_id', 'link_goods_id', 'admin_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'goods_id' => 'Goods ID',
            'link_goods_id' => 'Link Goods ID',
            'is_double' => 'Is Double',
            'admin_id' => 'Admin ID',
        ];
    }
}
