<?php

namespace app\models;

use think\Model;

/**
 * Class AccountLog
 * @package app\models
 * @property integer $log_id 自增ID
 * @property integer $user_id 用户ID
 * @property double $user_money 金额
 * @property double $frozen_money 冻结金额
 * @property integer $rank_points 等级积分
 * @property integer $pay_points 支付积分
 * @property integer $change_time 变更时间
 * @property string $change_desc 变更描述
 * @property integer $change_type 变更类型
 */
class AccountLog extends Model
{
    protected $table = 'account_log';

    protected $pk = 'log_id';

}
