<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%area_region}}".
 *
 * @property int $shipping_area_id
 * @property int $region_id
 */
class AreaRegion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%area_region}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shipping_area_id', 'region_id'], 'required'],
            [['shipping_area_id', 'region_id'], 'integer'],
            [['shipping_area_id', 'region_id'], 'unique', 'targetAttribute' => ['shipping_area_id', 'region_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'shipping_area_id' => 'Shipping Area ID',
            'region_id' => 'Region ID',
        ];
    }
}
