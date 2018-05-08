<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EmailSendlist
 * @package App\Models
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
