<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%user_account}}".
 *
 * @property int $id
 * @property int $user_id
 * @property string $admin_user
 * @property string $amount
 * @property int $add_time
 * @property int $paid_time
 * @property string $admin_note
 * @property string $user_note
 * @property int $process_type
 * @property string $payment
 * @property int $is_paid
 */
class UserAccount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_account}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'add_time', 'paid_time'], 'integer'],
            [['admin_user', 'amount', 'admin_note', 'user_note', 'payment'], 'required'],
            [['amount'], 'number'],
            [['admin_user', 'admin_note', 'user_note'], 'string', 'max' => 255],
            [['process_type', 'is_paid'], 'string', 'max' => 1],
            [['payment'], 'string', 'max' => 90],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'admin_user' => 'Admin User',
            'amount' => 'Amount',
            'add_time' => 'Add Time',
            'paid_time' => 'Paid Time',
            'admin_note' => 'Admin Note',
            'user_note' => 'User Note',
            'process_type' => 'Process Type',
            'payment' => 'Payment',
            'is_paid' => 'Is Paid',
        ];
    }
}
