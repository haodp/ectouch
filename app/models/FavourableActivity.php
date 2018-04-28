<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%favourable_activity}}".
 *
 * @property int $act_id
 * @property string $act_name
 * @property int $start_time
 * @property int $end_time
 * @property string $user_rank
 * @property int $act_range
 * @property string $act_range_ext
 * @property string $min_amount
 * @property string $max_amount
 * @property int $act_type
 * @property string $act_type_ext
 * @property string $gift
 * @property int $sort_order
 */
class FavourableActivity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%favourable_activity}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['act_name', 'start_time', 'end_time', 'user_rank', 'act_range', 'act_range_ext', 'min_amount', 'max_amount', 'act_type', 'act_type_ext', 'gift'], 'required'],
            [['start_time', 'end_time'], 'integer'],
            [['min_amount', 'max_amount', 'act_type_ext'], 'number'],
            [['gift'], 'string'],
            [['act_name', 'user_rank', 'act_range_ext'], 'string', 'max' => 255],
            [['act_range', 'act_type', 'sort_order'], 'string', 'max' => 3],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'act_id' => 'Act ID',
            'act_name' => 'Act Name',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'user_rank' => 'User Rank',
            'act_range' => 'Act Range',
            'act_range_ext' => 'Act Range Ext',
            'min_amount' => 'Min Amount',
            'max_amount' => 'Max Amount',
            'act_type' => 'Act Type',
            'act_type_ext' => 'Act Type Ext',
            'gift' => 'Gift',
            'sort_order' => 'Sort Order',
        ];
    }
}
