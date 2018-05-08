<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Article
 * @package App\Models
 * @property $cat_id
 * @property $title
 * @property $content
 * @property $author
 * @property $author_email
 * @property $keywords
 * @property $article_type
 * @property $is_open
 * @property $add_time
 * @property $file_url
 * @property $open_type
 * @property $link
 * @property $description
 */
class Article extends Model
{
    protected $table = 'article';

    protected $pk = 'article_id';

}
