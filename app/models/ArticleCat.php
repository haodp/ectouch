<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%article_cat}}".
 *
 * @property int $cat_id
 * @property string $cat_name
 * @property int $cat_type
 * @property string $keywords
 * @property string $cat_desc
 * @property int $sort_order
 * @property int $show_in_nav
 * @property int $parent_id
 */
class ArticleCat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%article_cat}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id'], 'integer'],
            [['cat_name', 'keywords', 'cat_desc'], 'string', 'max' => 255],
            [['cat_type', 'show_in_nav'], 'string', 'max' => 1],
            [['sort_order'], 'string', 'max' => 3],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cat_id' => 'Cat ID',
            'cat_name' => 'Cat Name',
            'cat_type' => 'Cat Type',
            'keywords' => 'Keywords',
            'cat_desc' => 'Cat Desc',
            'sort_order' => 'Sort Order',
            'show_in_nav' => 'Show In Nav',
            'parent_id' => 'Parent ID',
        ];
    }
}
