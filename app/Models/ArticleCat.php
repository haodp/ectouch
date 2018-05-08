<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ArticleCat
 * @package App\Models
 * @property $cat_name
 * @property $cat_type
 * @property $keywords
 * @property $cat_desc
 * @property $sort_order
 * @property $show_in_nav
 * @property $parent_id
 */
class ArticleCat extends Model
{
    protected $table = 'article_cat';

    protected $pk = 'cat_id';

}
