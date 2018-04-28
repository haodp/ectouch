<?php

namespace app\models;

use think\Model;

/**
 * Class MailTemplates
 * @package app\models
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
