<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%ad_custom}}".
 *
 * @property int $ad_id
 * @property int $ad_type
 * @property string $ad_name
 * @property int $add_time
 * @property string $content
 * @property string $url
 * @property int $ad_status
 */
class AdCustom extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ad_custom}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['add_time'], 'integer'],
            [['content'], 'string'],
            [['ad_type'], 'string', 'max' => 1],
            [['ad_name'], 'string', 'max' => 60],
            [['url'], 'string', 'max' => 255],
            [['ad_status'], 'string', 'max' => 3],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ad_id' => 'Ad ID',
            'ad_type' => 'Ad Type',
            'ad_name' => 'Ad Name',
            'add_time' => 'Add Time',
            'content' => 'Content',
            'url' => 'Url',
            'ad_status' => 'Ad Status',
        ];
    }
}
