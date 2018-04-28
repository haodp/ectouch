<?php

namespace app\models;

use think\Model;

/**
 * Class ArticleCat
 * @package app\models
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
