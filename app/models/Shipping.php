<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%shipping}}".
 *
 * @property int $shipping_id
 * @property string $shipping_code
 * @property string $shipping_name
 * @property string $shipping_desc
 * @property string $insure
 * @property int $support_cod
 * @property int $enabled
 * @property string $shipping_print
 * @property string $print_bg
 * @property string $config_lable
 * @property int $print_model
 * @property int $shipping_order
 */
class Shipping extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shipping}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shipping_print'], 'required'],
            [['shipping_print', 'config_lable'], 'string'],
            [['shipping_code'], 'string', 'max' => 20],
            [['shipping_name'], 'string', 'max' => 120],
            [['shipping_desc', 'print_bg'], 'string', 'max' => 255],
            [['insure'], 'string', 'max' => 10],
            [['support_cod', 'enabled', 'print_model'], 'string', 'max' => 1],
            [['shipping_order'], 'string', 'max' => 3],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'shipping_id' => 'Shipping ID',
            'shipping_code' => 'Shipping Code',
            'shipping_name' => 'Shipping Name',
            'shipping_desc' => 'Shipping Desc',
            'insure' => 'Insure',
            'support_cod' => 'Support Cod',
            'enabled' => 'Enabled',
            'shipping_print' => 'Shipping Print',
            'print_bg' => 'Print Bg',
            'config_lable' => 'Config Lable',
            'print_model' => 'Print Model',
            'shipping_order' => 'Shipping Order',
        ];
    }
}
