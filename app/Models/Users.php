<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%users}}".
 *
 * @property int $user_id
 * @property string $email
 * @property string $user_name
 * @property string $password
 * @property string $question
 * @property string $answer
 * @property int $sex
 * @property string $birthday
 * @property string $user_money
 * @property string $frozen_money
 * @property int $pay_points
 * @property int $rank_points
 * @property int $address_id
 * @property int $reg_time
 * @property int $last_login
 * @property string $last_time
 * @property string $last_ip
 * @property int $visit_count
 * @property int $user_rank
 * @property int $is_special
 * @property string $ec_salt
 * @property string $salt
 * @property int $parent_id
 * @property int $flag
 * @property string $alias
 * @property string $msn
 * @property string $qq
 * @property string $office_phone
 * @property string $home_phone
 * @property string $mobile_phone
 * @property int $is_validated
 * @property string $credit_line
 * @property string $passwd_question
 * @property string $passwd_answer
 */
class Users extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%users}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['birthday', 'last_time'], 'safe'],
            [['user_money', 'frozen_money', 'credit_line'], 'number'],
            [['pay_points', 'rank_points', 'address_id', 'reg_time', 'last_login', 'visit_count', 'parent_id'], 'integer'],
            [['alias', 'msn', 'qq', 'office_phone', 'home_phone', 'mobile_phone', 'credit_line'], 'required'],
            [['email', 'user_name', 'alias', 'msn'], 'string', 'max' => 60],
            [['password'], 'string', 'max' => 32],
            [['question', 'answer', 'passwd_answer'], 'string', 'max' => 255],
            [['sex'], 'string', 'max' => 1],
            [['last_ip'], 'string', 'max' => 15],
            [['user_rank', 'is_special', 'flag', 'is_validated'], 'string', 'max' => 3],
            [['ec_salt', 'salt'], 'string', 'max' => 10],
            [['qq', 'office_phone', 'home_phone', 'mobile_phone'], 'string', 'max' => 20],
            [['passwd_question'], 'string', 'max' => 50],
            [['user_name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'email' => 'Email',
            'user_name' => 'User Name',
            'password' => 'Password',
            'question' => 'Question',
            'answer' => 'Answer',
            'sex' => 'Sex',
            'birthday' => 'Birthday',
            'user_money' => 'User Money',
            'frozen_money' => 'Frozen Money',
            'pay_points' => 'Pay Points',
            'rank_points' => 'Rank Points',
            'address_id' => 'Address ID',
            'reg_time' => 'Reg Time',
            'last_login' => 'Last Login',
            'last_time' => 'Last Time',
            'last_ip' => 'Last Ip',
            'visit_count' => 'Visit Count',
            'user_rank' => 'User Rank',
            'is_special' => 'Is Special',
            'ec_salt' => 'Ec Salt',
            'salt' => 'Salt',
            'parent_id' => 'Parent ID',
            'flag' => 'Flag',
            'alias' => 'Alias',
            'msn' => 'Msn',
            'qq' => 'Qq',
            'office_phone' => 'Office Phone',
            'home_phone' => 'Home Phone',
            'mobile_phone' => 'Mobile Phone',
            'is_validated' => 'Is Validated',
            'credit_line' => 'Credit Line',
            'passwd_question' => 'Passwd Question',
            'passwd_answer' => 'Passwd Answer',
        ];
    }
}
