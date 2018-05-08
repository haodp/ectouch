<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserFeed
 * @package App\Models
 * @property $user_id
 * @property $value_id
 * @property $goods_id
 * @property $feed_type
 * @property $is_feed
 */
class UserFeed extends Model
{
    protected $table = 'user_feed';

    protected $pk = 'feed_id';

}
