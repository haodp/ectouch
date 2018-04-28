<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%email_sendlist}}".
 *
 * @property int $id
 * @property string $email
 * @property int $template_id
 * @property string $email_content
 * @property int $error
 * @property int $pri
 * @property int $last_send
 */
class EmailSendlist extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%email_sendlist}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'template_id', 'email_content', 'pri', 'last_send'], 'required'],
            [['template_id', 'last_send'], 'integer'],
            [['email_content'], 'string'],
            [['email'], 'string', 'max' => 100],
            [['error'], 'string', 'max' => 1],
            [['pri'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'template_id' => 'Template ID',
            'email_content' => 'Email Content',
            'error' => 'Error',
            'pri' => 'Pri',
            'last_send' => 'Last Send',
        ];
    }
}
