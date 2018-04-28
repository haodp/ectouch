<?php

namespace app\models;

use think\Model;

/**
 * Class User
 * @package app\models
 * @package app\models
 * @property $email
 * @property $user_name
 * @property $password
 * @property $question
 * @property $answer
 * @property $sex
 * @property $birthday
 * @property $user_money
 * @property $frozen_money
 * @property $pay_points
 * @property $rank_points
 * @property $address_id
 * @property $reg_time
 * @property $last_login
 * @property $last_time
 * @property $last_ip
 * @property $visit_count
 * @property $user_rank
 * @property $is_special
 * @property $ec_salt
 * @property $salt
 * @property $parent_id
 * @property $flag
 * @property $alias
 * @property $msn
 * @property $qq
 * @property $office_phone
 * @property $home_phone
 * @property $mobile_phone
 * @property $is_validated
 * @property $credit_line
 * @property $passwd_question
 * @property $passwd_answer
 */
class User extends Model
{
    protected $table = 'users';

    protected $pk = 'user_id';

}
