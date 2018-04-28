<?php

namespace app\models;

use think\Model;

/**
 * Class RegExtendInfo
 * @package app\models
 * @property $user_id
 * @property $reg_field_id
 * @property $content
 */
class RegExtendInfo extends Model
{
    protected $table = 'reg_extend_info';

    protected $pk = 'Id';

}
