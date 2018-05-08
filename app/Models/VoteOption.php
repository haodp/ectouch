<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class VoteOption
 * @package App\Models
 * @property $vote_id
 * @property $option_name
 * @property $option_count
 * @property $option_order
 */
class VoteOption extends Model
{
    protected $table = 'vote_option';

    protected $pk = 'option_id';

}
