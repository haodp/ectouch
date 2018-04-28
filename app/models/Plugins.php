<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%plugins}}".
 *
 * @property string $code
 * @property string $version
 * @property string $library
 * @property int $assign
 * @property int $install_date
 */
class Plugins extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%plugins}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code'], 'required'],
            [['install_date'], 'integer'],
            [['code'], 'string', 'max' => 30],
            [['version'], 'string', 'max' => 10],
            [['library'], 'string', 'max' => 255],
            [['assign'], 'string', 'max' => 1],
            [['code'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'code' => 'Code',
            'version' => 'Version',
            'library' => 'Library',
            'assign' => 'Assign',
            'install_date' => 'Install Date',
        ];
    }
}
