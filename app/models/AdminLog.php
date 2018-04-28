<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%admin_log}}".
 *
 * @property int $log_id
 * @property int $log_time
 * @property int $user_id
 * @property string $log_info
 * @property string $ip_address
 */
class AdminLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['log_time'], 'integer'],
            [['user_id'], 'string', 'max' => 3],
            [['log_info'], 'string', 'max' => 255],
            [['ip_address'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'log_id' => 'Log ID',
            'log_time' => 'Log Time',
            'user_id' => 'User ID',
            'log_info' => 'Log Info',
            'ip_address' => 'Ip Address',
        ];
    }
}
