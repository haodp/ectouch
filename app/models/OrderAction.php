<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%order_action}}".
 *
 * @property int $action_id
 * @property int $order_id
 * @property string $action_user
 * @property int $order_status
 * @property int $shipping_status
 * @property int $pay_status
 * @property int $action_place
 * @property string $action_note
 * @property int $log_time
 */
class OrderAction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_action}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'log_time'], 'integer'],
            [['action_user'], 'string', 'max' => 30],
            [['order_status', 'shipping_status', 'pay_status', 'action_place'], 'string', 'max' => 1],
            [['action_note'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'action_id' => 'Action ID',
            'order_id' => 'Order ID',
            'action_user' => 'Action User',
            'order_status' => 'Order Status',
            'shipping_status' => 'Shipping Status',
            'pay_status' => 'Pay Status',
            'action_place' => 'Action Place',
            'action_note' => 'Action Note',
            'log_time' => 'Log Time',
        ];
    }
}
