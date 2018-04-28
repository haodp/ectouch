<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%payment}}".
 *
 * @property int $pay_id
 * @property string $pay_code
 * @property string $pay_name
 * @property string $pay_fee
 * @property string $pay_desc
 * @property int $pay_order
 * @property string $pay_config
 * @property int $enabled
 * @property int $is_cod
 * @property int $is_online
 */
class Payment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%payment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pay_desc', 'pay_config'], 'required'],
            [['pay_desc', 'pay_config'], 'string'],
            [['pay_code'], 'string', 'max' => 20],
            [['pay_name'], 'string', 'max' => 120],
            [['pay_fee'], 'string', 'max' => 10],
            [['pay_order'], 'string', 'max' => 3],
            [['enabled', 'is_cod', 'is_online'], 'string', 'max' => 1],
            [['pay_code'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pay_id' => 'Pay ID',
            'pay_code' => 'Pay Code',
            'pay_name' => 'Pay Name',
            'pay_fee' => 'Pay Fee',
            'pay_desc' => 'Pay Desc',
            'pay_order' => 'Pay Order',
            'pay_config' => 'Pay Config',
            'enabled' => 'Enabled',
            'is_cod' => 'Is Cod',
            'is_online' => 'Is Online',
        ];
    }
}
