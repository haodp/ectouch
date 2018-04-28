<?php

namespace app\models;

use think\Model;

/**
 * Class FriendLink
 * @package app\models
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
