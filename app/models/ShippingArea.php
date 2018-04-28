<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%shipping_area}}".
 *
 * @property int $shipping_area_id
 * @property string $shipping_area_name
 * @property int $shipping_id
 * @property string $configure
 */
class ShippingArea extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shipping_area}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['configure'], 'required'],
            [['configure'], 'string'],
            [['shipping_area_name'], 'string', 'max' => 150],
            [['shipping_id'], 'string', 'max' => 3],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'shipping_area_id' => 'Shipping Area ID',
            'shipping_area_name' => 'Shipping Area Name',
            'shipping_id' => 'Shipping ID',
            'configure' => 'Configure',
        ];
    }
}
