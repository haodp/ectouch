<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%attribute}}".
 *
 * @property int $attr_id
 * @property int $cat_id
 * @property string $attr_name
 * @property int $attr_input_type
 * @property int $attr_type
 * @property string $attr_values
 * @property int $attr_index
 * @property int $sort_order
 * @property int $is_linked
 * @property int $attr_group
 */
class Attribute extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%attribute}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cat_id'], 'integer'],
            [['attr_values'], 'required'],
            [['attr_values'], 'string'],
            [['attr_name'], 'string', 'max' => 60],
            [['attr_input_type', 'attr_type', 'attr_index', 'is_linked', 'attr_group'], 'string', 'max' => 1],
            [['sort_order'], 'string', 'max' => 3],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'attr_id' => 'Attr ID',
            'cat_id' => 'Cat ID',
            'attr_name' => 'Attr Name',
            'attr_input_type' => 'Attr Input Type',
            'attr_type' => 'Attr Type',
            'attr_values' => 'Attr Values',
            'attr_index' => 'Attr Index',
            'sort_order' => 'Sort Order',
            'is_linked' => 'Is Linked',
            'attr_group' => 'Attr Group',
        ];
    }
}
