<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%article}}".
 *
 * @property int $article_id
 * @property int $cat_id
 * @property string $title
 * @property string $content
 * @property string $author
 * @property string $author_email
 * @property string $keywords
 * @property int $article_type
 * @property int $is_open
 * @property int $add_time
 * @property string $file_url
 * @property int $open_type
 * @property string $link
 * @property string $description
 */
class Article extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%article}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cat_id', 'add_time'], 'integer'],
            [['content'], 'required'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 150],
            [['author'], 'string', 'max' => 30],
            [['author_email'], 'string', 'max' => 60],
            [['keywords', 'file_url', 'link', 'description'], 'string', 'max' => 255],
            [['article_type', 'is_open', 'open_type'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'article_id' => 'Article ID',
            'cat_id' => 'Cat ID',
            'title' => 'Title',
            'content' => 'Content',
            'author' => 'Author',
            'author_email' => 'Author Email',
            'keywords' => 'Keywords',
            'article_type' => 'Article Type',
            'is_open' => 'Is Open',
            'add_time' => 'Add Time',
            'file_url' => 'File Url',
            'open_type' => 'Open Type',
            'link' => 'Link',
            'description' => 'Description',
        ];
    }
}
