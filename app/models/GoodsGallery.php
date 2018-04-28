<?php

namespace app\models;

use think\Model;

/**
 * Class GoodsGallery
 * @package app\models
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
