<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%user_rank}}".
 *
 * @property int $rank_id
 * @property string $rank_name
 * @property int $min_points
 * @property int $max_points
 * @property int $discount
 * @property int $show_price
 * @property int $special_rank
 */
class UserRank extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_rank}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['min_points', 'max_points'], 'integer'],
            [['rank_name'], 'string', 'max' => 30],
            [['discount'], 'string', 'max' => 3],
            [['show_price', 'special_rank'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rank_id' => 'Rank ID',
            'rank_name' => 'Rank Name',
            'min_points' => 'Min Points',
            'max_points' => 'Max Points',
            'discount' => 'Discount',
            'show_price' => 'Show Price',
            'special_rank' => 'Special Rank',
        ];
    }
}
