<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%cat_recommend}}".
 *
 * @property int $cat_id
 * @property int $recommend_type
 */
class CatRecommend extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cat_recommend}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cat_id', 'recommend_type'], 'required'],
            [['cat_id'], 'integer'],
            [['recommend_type'], 'string', 'max' => 1],
            [['cat_id', 'recommend_type'], 'unique', 'targetAttribute' => ['cat_id', 'recommend_type']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cat_id' => 'Cat ID',
            'recommend_type' => 'Recommend Type',
        ];
    }
}
