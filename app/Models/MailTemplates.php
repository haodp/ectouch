<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MailTemplates
 * @package App\Models
 * @property $template_code
 * @property $is_html
 * @property $template_subject
 * @property $template_content
 * @property $last_modify
 * @property $last_send
 * @property $type
 */
class MailTemplates extends Model
{
    protected $table = 'mail_templates';

    protected $pk = 'template_id';

}
