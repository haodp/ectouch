<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Feedback
 * @package App\Models
 * @property $parent_id
 * @property $user_id
 * @property $user_name
 * @property $user_email
 * @property $msg_title
 * @property $msg_type
 * @property $msg_status
 * @property $msg_content
 * @property $msg_time
 * @property $message_img
 * @property $order_id
 * @property $msg_area
 */
class Feedback extends Model
{
    protected $table = 'feedback';

    protected $pk = 'msg_id';

}
