<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GoodsGallery
 * @package App\Models
 * @property $goods_id
 * @property $img_url
 * @property $img_desc
 * @property $thumb_url
 * @property $img_original
 */
class GoodsGallery extends Model
{
    protected $table = 'goods_gallery';

    protected $pk = 'img_id';

}
