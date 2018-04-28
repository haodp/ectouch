<?php

namespace app\models;

use think\Model;

/**
 * Class Brand
 * @package app\models
 * @property string $brand_name 品牌名称
 * @property string $brand_logo 品牌图片
 * @property string $brand_desc 品牌描述
 * @property string $site_url 品牌官网
 * @property integer $sort_order 排序
 * @property integer $is_show 是否显示
 */
class Brand extends Model
{
    protected $table = 'brand';

    protected $pk = 'brand_id';

}
