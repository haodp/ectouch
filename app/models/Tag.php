<?php

namespace app\models;

use think\Model;

/**
 * Class Tag
 * @package app\models
 * @property $user_id
 * @property $goods_id
 * @property $tag_words
 */
class Tag extends Model
{
    protected $table = 'tag';

    protected $pk = 'tag_id';

}
