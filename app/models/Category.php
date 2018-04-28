<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%category}}".
 *
 * @property int $cat_id
 * @property string $cat_name
 * @property string $keywords
 * @property string $cat_desc
 * @property int $parent_id
 * @property int $sort_order
 * @property string $template_file
 * @property string $measure_unit
 * @property int $show_in_nav
 * @property string $style
 * @property int $is_show
 * @property int $grade
 * @property string $filter_attr
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id'], 'integer'],
            [['style'], 'required'],
            [['cat_name'], 'string', 'max' => 90],
            [['keywords', 'cat_desc', 'filter_attr'], 'string', 'max' => 255],
            [['sort_order', 'show_in_nav', 'is_show'], 'string', 'max' => 1],
            [['template_file'], 'string', 'max' => 50],
            [['measure_unit'], 'string', 'max' => 15],
            [['style'], 'string', 'max' => 150],
            [['grade'], 'string', 'max' => 4],
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
            'keywords' => 'Keywords',
            'cat_desc' => 'Cat Desc',
            'parent_id' => 'Parent ID',
            'sort_order' => 'Sort Order',
            'template_file' => 'Template File',
            'measure_unit' => 'Measure Unit',
            'show_in_nav' => 'Show In Nav',
            'style' => 'Style',
            'is_show' => 'Is Show',
            'grade' => 'Grade',
            'filter_attr' => 'Filter Attr',
        ];
    }
}
