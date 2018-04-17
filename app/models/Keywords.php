<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%keywords}}".
 *
 * @property string $date
 * @property string $searchengine
 * @property string $keyword
 * @property int $count
 */
class Keywords extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%keywords}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date', 'searchengine', 'keyword'], 'required'],
            [['date'], 'safe'],
            [['count'], 'integer'],
            [['searchengine'], 'string', 'max' => 20],
            [['keyword'], 'string', 'max' => 90],
            [['date', 'searchengine', 'keyword'], 'unique', 'targetAttribute' => ['date', 'searchengine', 'keyword']],
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
            'keyword' => 'Keyword',
            'count' => 'Count',
        ];
    }
}