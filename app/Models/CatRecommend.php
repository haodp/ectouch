<?php

namespace app\models;

use dao\Model;

/**
 * Class CatRecommend
 * @package app\models
 * @property integer $cat_id
 * @property $recommend_type
 */
class CatRecommend extends Model
{
    protected $table = 'cat_recommend';

}
