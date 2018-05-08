<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AdminLog
 * @package App\Models
 * @property integer $log_id 自增ID
 * @property integer $log_time 日志时间
 * @property integer $user_id 用户ID
 * @property string $log_info 日志内容
 * @property string $ip_address IP地址
 */
class AdminLog extends Model
{
    protected $table = 'admin_log';

    protected $pk = 'log_id';

}
