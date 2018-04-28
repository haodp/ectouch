<?php

namespace app\models;

use think\Model;

/**
 * Class EmailSendlist
 * @package app\models
 * @property $email
 * @property $template_id
 * @property $email_content
 * @property $error
 * @property $pri
 * @property $last_send
 */
class EmailSendlist extends Model
{
    protected $table = 'email_sendlist';

}
