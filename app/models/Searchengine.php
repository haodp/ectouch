<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%searchengine}}".
 *
 * @property string $date
 * @property string $searchengine
 * @property int $count
 */
class Searchengine extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%searchengine}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date', 'searchengine'], 'required'],
            [['date'], 'safe'],
            [['count'], 'integer'],
            [['searchengine'], 'string', 'max' => 20],
            [['date', 'searchengine'], 'unique', 'targetAttribute' => ['date', 'searchengine']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'date' => 'Date',
            'searchengine' => 'Searchengine',
            'count' => 'Count',
        ];
    }
}
