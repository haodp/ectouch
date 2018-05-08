<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CatRecommend
 * @package App\Models
 * @property integer $cat_id
 * @property $recommend_type
 */
class CatRecommend extends Model
{
    protected $table = 'cat_recommend';

}
