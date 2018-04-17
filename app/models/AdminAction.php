<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%admin_action}}".
 *
 * @property int $action_id
 * @property int $parent_id
 * @property string $action_code
 * @property string $relevance
 */
class AdminAction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_action}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id'], 'string', 'max' => 3],
            [['action_code', 'relevance'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'action_id' => 'Action ID',
            'parent_id' => 'Parent ID',
            'action_code' => 'Action Code',
            'relevance' => 'Relevance',
        ];
    }
}