<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RegExtendInfo
 * @package App\Models
 * @property $user_id
 * @property $reg_field_id
 * @property $content
 */
class RegExtendInfo extends Model
{
    protected $table = 'reg_extend_info';

    protected $pk = 'Id';

}
