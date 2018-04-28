<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%bonus_type}}".
 *
 * @property int $type_id
 * @property string $type_name
 * @property string $type_money
 * @property int $send_type
 * @property string $min_amount
 * @property string $max_amount
 * @property int $send_start_date
 * @property int $send_end_date
 * @property int $use_start_date
 * @property int $use_end_date
 * @property string $min_goods_amount
 */
class BonusType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%bonus_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_money', 'min_amount', 'max_amount', 'min_goods_amount'], 'number'],
            [['send_start_date', 'send_end_date', 'use_start_date', 'use_end_date'], 'integer'],
            [['type_name'], 'string', 'max' => 60],
            [['send_type'], 'string', 'max' => 3],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'type_id' => 'Type ID',
            'type_name' => 'Type Name',
            'type_money' => 'Type Money',
            'send_type' => 'Send Type',
            'min_amount' => 'Min Amount',
            'max_amount' => 'Max Amount',
            'send_start_date' => 'Send Start Date',
            'send_end_date' => 'Send End Date',
            'use_start_date' => 'Use Start Date',
            'use_end_date' => 'Use End Date',
            'min_goods_amount' => 'Min Goods Amount',
        ];
    }
}
