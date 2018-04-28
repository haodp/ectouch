<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%reg_fields}}".
 *
 * @property int $id
 * @property string $reg_field_name
 * @property int $dis_order
 * @property int $display
 * @property int $type
 * @property int $is_need
 */
class RegFields extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%reg_fields}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['reg_field_name'], 'required'],
            [['reg_field_name'], 'string', 'max' => 60],
            [['dis_order'], 'string', 'max' => 3],
            [['display', 'type', 'is_need'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'reg_field_name' => 'Reg Field Name',
            'dis_order' => 'Dis Order',
            'display' => 'Display',
            'type' => 'Type',
            'is_need' => 'Is Need',
        ];
    }
}
