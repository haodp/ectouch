<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%template}}".
 *
 * @property string $filename
 * @property string $region
 * @property string $library
 * @property int $sort_order
 * @property int $id
 * @property int $number
 * @property int $type
 * @property string $theme
 * @property string $remarks
 */
class Template extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%template}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['filename', 'remarks'], 'string', 'max' => 30],
            [['region', 'library'], 'string', 'max' => 40],
            [['sort_order', 'number', 'type'], 'string', 'max' => 1],
            [['theme'], 'string', 'max' => 60],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'filename' => 'Filename',
            'region' => 'Region',
            'library' => 'Library',
            'sort_order' => 'Sort Order',
            'id' => 'ID',
            'number' => 'Number',
            'type' => 'Type',
            'theme' => 'Theme',
            'remarks' => 'Remarks',
        ];
    }
}
