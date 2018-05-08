<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Tag
 * @package App\Models
 * @property $user_id
 * @property $goods_id
 * @property $tag_words
 */
class Tag extends Model
{
    protected $table = 'tag';

    protected $pk = 'tag_id';

}
