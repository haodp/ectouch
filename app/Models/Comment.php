<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Comment
 * @package App\Models
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
