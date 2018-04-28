<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%auto_manage}}".
 *
 * @property int $item_id
 * @property string $type
 * @property int $starttime
 * @property int $endtime
 */
class AutoManage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auto_manage}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'type', 'starttime', 'endtime'], 'required'],
            [['item_id', 'starttime', 'endtime'], 'integer'],
            [['type'], 'string', 'max' => 10],
            [['item_id', 'type'], 'unique', 'targetAttribute' => ['item_id', 'type']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'item_id' => 'Item ID',
            'type' => 'Type',
            'starttime' => 'Starttime',
            'endtime' => 'Endtime',
        ];
    }
}
