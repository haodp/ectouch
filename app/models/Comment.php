<?php

namespace app\models;

use think\Model;

/**
 * Class Comment
 * @package app\models
 * @property $comment_type
 * @property $id_value
 * @property $email
 * @property $user_name
 * @property $content
 * @property $comment_rank
 * @property $add_time
 * @property $ip_address
 * @property $status
 * @property $parent_id
 * @property $user_id
 */
class Comment extends Model
{
    protected $table = 'comment';

    protected $pk = 'comment_id';

}
