<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%goods_article}}".
 *
 * @property int $goods_id
 * @property int $article_id
 * @property int $admin_id
 */
class GoodsArticle extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods_article}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'article_id', 'admin_id'], 'required'],
            [['goods_id', 'article_id'], 'integer'],
            [['admin_id'], 'string', 'max' => 3],
            [['goods_id', 'article_id', 'admin_id'], 'unique', 'targetAttribute' => ['goods_id', 'article_id', 'admin_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'goods_id' => 'Goods ID',
            'article_id' => 'Article ID',
            'admin_id' => 'Admin ID',
        ];
    }
}
