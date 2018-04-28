<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%comment}}".
 *
 * @property int $comment_id
 * @property int $comment_type
 * @property int $id_value
 * @property string $email
 * @property string $user_name
 * @property string $content
 * @property int $comment_rank
 * @property int $add_time
 * @property string $ip_address
 * @property int $status
 * @property int $parent_id
 * @property int $user_id
 */
class Comment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%comment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_value', 'add_time', 'parent_id', 'user_id'], 'integer'],
            [['content'], 'required'],
            [['content'], 'string'],
            [['comment_type', 'status'], 'string', 'max' => 3],
            [['email', 'user_name'], 'string', 'max' => 60],
            [['comment_rank'], 'string', 'max' => 1],
            [['ip_address'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'comment_id' => 'Comment ID',
            'comment_type' => 'Comment Type',
            'id_value' => 'Id Value',
            'email' => 'Email',
            'user_name' => 'User Name',
            'content' => 'Content',
            'comment_rank' => 'Comment Rank',
            'add_time' => 'Add Time',
            'ip_address' => 'Ip Address',
            'status' => 'Status',
            'parent_id' => 'Parent ID',
            'user_id' => 'User ID',
        ];
    }
}
