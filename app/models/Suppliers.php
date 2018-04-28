<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%suppliers}}".
 *
 * @property int $suppliers_id
 * @property string $suppliers_name
 * @property string $suppliers_desc
 * @property int $is_check
 */
class Suppliers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%suppliers}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['suppliers_desc'], 'string'],
            [['suppliers_name'], 'string', 'max' => 255],
            [['is_check'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'suppliers_id' => 'Suppliers ID',
            'suppliers_name' => 'Suppliers Name',
            'suppliers_desc' => 'Suppliers Desc',
            'is_check' => 'Is Check',
        ];
    }
}
