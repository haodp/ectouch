<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%agency}}".
 *
 * @property int $agency_id
 * @property string $agency_name
 * @property string $agency_desc
 */
class Agency extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%agency}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['agency_name', 'agency_desc'], 'required'],
            [['agency_desc'], 'string'],
            [['agency_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'agency_id' => 'Agency ID',
            'agency_name' => 'Agency Name',
            'agency_desc' => 'Agency Desc',
        ];
    }
}
