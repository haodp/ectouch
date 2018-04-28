<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%adsense}}".
 *
 * @property int $from_ad
 * @property string $referer
 * @property int $clicks
 */
class Adsense extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%adsense}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['from_ad', 'clicks'], 'integer'],
            [['referer'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'from_ad' => 'From Ad',
            'referer' => 'Referer',
            'clicks' => 'Clicks',
        ];
    }
}
