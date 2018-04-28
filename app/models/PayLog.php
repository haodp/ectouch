<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%pay_log}}".
 *
 * @property int $log_id
 * @property int $order_id
 * @property string $order_amount
 * @property int $order_type
 * @property int $is_paid
 */
class PayLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pay_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id'], 'integer'],
            [['order_amount'], 'required'],
            [['order_amount'], 'number'],
            [['order_type', 'is_paid'], 'string', 'max' => 1],
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
            'order_amount' => 'Order Amount',
            'order_type' => 'Order Type',
            'is_paid' => 'Is Paid',
        ];
    }
}
