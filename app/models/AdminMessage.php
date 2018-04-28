<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%admin_message}}".
 *
 * @property int $message_id
 * @property int $sender_id
 * @property int $receiver_id
 * @property int $sent_time
 * @property int $read_time
 * @property int $readed
 * @property int $deleted
 * @property string $title
 * @property string $message
 */
class AdminMessage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_message}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sent_time', 'read_time'], 'integer'],
            [['message'], 'required'],
            [['message'], 'string'],
            [['sender_id', 'receiver_id'], 'string', 'max' => 3],
            [['readed', 'deleted'], 'string', 'max' => 1],
            [['title'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'message_id' => 'Message ID',
            'sender_id' => 'Sender ID',
            'receiver_id' => 'Receiver ID',
            'sent_time' => 'Sent Time',
            'read_time' => 'Read Time',
            'readed' => 'Readed',
            'deleted' => 'Deleted',
            'title' => 'Title',
            'message' => 'Message',
        ];
    }
}
