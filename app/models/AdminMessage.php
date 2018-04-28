<?php

namespace app\models;

use think\Model;

/**
 * Class AdminMessage
 * @package app\models
 * @property $sender_id
 * @property $receiver_id
 * @property $sent_time
 * @property $read_time
 * @property $readed
 * @property $deleted
 * @property $title
 * @property $message
 */
class AdminMessage extends Model
{
    protected $table = 'admin_message';

    protected $pk = 'message_id';

}
