<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%user_feed}}".
 *
 * @property int $feed_id
 * @property int $user_id
 * @property int $value_id
 * @property int $goods_id
 * @property int $feed_type
 * @property int $is_feed
 */
class UserFeed extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_feed}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'value_id', 'goods_id'], 'integer'],
            [['feed_type', 'is_feed'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'feed_id' => 'Feed ID',
            'user_id' => 'User ID',
            'value_id' => 'Value ID',
            'goods_id' => 'Goods ID',
            'feed_type' => 'Feed Type',
            'is_feed' => 'Is Feed',
        ];
    }
}
