<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%pack}}".
 *
 * @property int $pack_id
 * @property string $pack_name
 * @property string $pack_img
 * @property string $pack_fee
 * @property int $free_money
 * @property string $pack_desc
 */
class Pack extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pack}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pack_fee'], 'number'],
            [['free_money'], 'integer'],
            [['pack_name'], 'string', 'max' => 120],
            [['pack_img', 'pack_desc'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pack_id' => 'Pack ID',
            'pack_name' => 'Pack Name',
            'pack_img' => 'Pack Img',
            'pack_fee' => 'Pack Fee',
            'free_money' => 'Free Money',
            'pack_desc' => 'Pack Desc',
        ];
    }
}