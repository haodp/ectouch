<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%package_goods}}".
 *
 * @property int $package_id
 * @property int $goods_id
 * @property int $product_id
 * @property int $goods_number
 * @property int $admin_id
 */
class PackageGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%package_goods}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['package_id', 'goods_id', 'product_id', 'admin_id'], 'required'],
            [['package_id', 'goods_id', 'product_id', 'goods_number'], 'integer'],
            [['admin_id'], 'string', 'max' => 3],
            [['package_id', 'goods_id', 'product_id', 'admin_id'], 'unique', 'targetAttribute' => ['package_id', 'goods_id', 'product_id', 'admin_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'package_id' => 'Package ID',
            'goods_id' => 'Goods ID',
            'product_id' => 'Product ID',
            'goods_number' => 'Goods Number',
            'admin_id' => 'Admin ID',
        ];
    }
}
