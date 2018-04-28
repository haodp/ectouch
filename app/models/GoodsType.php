<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%goods_type}}".
 *
 * @property int $cat_id
 * @property string $cat_name
 * @property int $enabled
 * @property string $attr_group
 */
class GoodsType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['attr_group'], 'required'],
            [['cat_name'], 'string', 'max' => 60],
            [['enabled'], 'string', 'max' => 1],
            [['attr_group'], 'string', 'max' => 255],
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
            'enabled' => 'Enabled',
            'attr_group' => 'Attr Group',
        ];
    }
}
