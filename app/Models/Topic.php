<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Topic
 * @package App\Models
 * @property $topic_id
 * @property $title
 * @property $intro
 * @property $start_time
 * @property $end_time
 * @property $data
 * @property $template
 * @property $css
 * @property $topic_img
 * @property $title_pic
 * @property $base_style
 * @property $htmls
 * @property $keywords
 * @property $description
 */
class Topic extends Model
{
    protected $table = 'topic';

}
