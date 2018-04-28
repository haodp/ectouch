<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%ad}}".
 *
 * @property int $ad_id
 * @property int $position_id
 * @property int $media_type
 * @property string $ad_name
 * @property string $ad_link
 * @property string $ad_code
 * @property int $start_time
 * @property int $end_time
 * @property string $link_man
 * @property string $link_email
 * @property string $link_phone
 * @property int $click_count
 * @property int $enabled
 */
class Ad extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ad}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['position_id', 'start_time', 'end_time', 'click_count'], 'integer'],
            [['ad_code'], 'required'],
            [['ad_code'], 'string'],
            [['media_type', 'enabled'], 'string', 'max' => 3],
            [['ad_name', 'link_man', 'link_email', 'link_phone'], 'string', 'max' => 60],
            [['ad_link'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ad_id' => 'Ad ID',
            'position_id' => 'Position ID',
            'media_type' => 'Media Type',
            'ad_name' => 'Ad Name',
            'ad_link' => 'Ad Link',
            'ad_code' => 'Ad Code',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'link_man' => 'Link Man',
            'link_email' => 'Link Email',
            'link_phone' => 'Link Phone',
            'click_count' => 'Click Count',
            'enabled' => 'Enabled',
        ];
    }
}
