<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%affiliate_log}}".
 *
 * @property int $log_id
 * @property int $order_id
 * @property int $time
 * @property int $user_id
 * @property string $user_name
 * @property string $money
 * @property int $point
 * @property int $separate_type
 */
class AffiliateLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%affiliate_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'time', 'user_id'], 'required'],
            [['order_id', 'time', 'user_id', 'point'], 'integer'],
            [['money'], 'number'],
            [['user_name'], 'string', 'max' => 60],
            [['separate_type'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'log_id' => 'Log ID',
            'order_id' => 'Order ID',
            'time' => 'Time',
            'user_id' => 'User ID',
            'user_name' => 'User Name',
            'money' => 'Money',
            'point' => 'Point',
            'separate_type' => 'Separate Type',
        ];
    }
}
