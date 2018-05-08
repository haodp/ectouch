<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FriendLink
 * @package App\Models
 * @property $link_name
 * @property $link_url
 * @property $link_logo
 * @property $show_order
 */
class FriendLink extends Model
{
    protected $table = 'friend_link';

    protected $pk = 'link_id';

}
