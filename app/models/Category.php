<?php

namespace app\models;

use think\Model;

/**
 * Class Category
 * @package app\models
 * @property string $cat_name 分类名称
 * @property string $keywords 分类关键词
 * @property string $cat_desc 分类描述
 * @property integer $parent_id 父级ID
 * @property integer $sort_order 排序
 * @property string $template_file 分类模板文件
 * @property string $measure_unit 数量单位
 * @property integer $show_in_nav 是否导航显示
 * @property string $style 分类样式文件
 * @property integer $is_show 是否显示
 * @property integer $grade 价格等级
 * @property string $filter_attr 筛选属性
 */
class Category extends Model
{
    protected $table = 'category';

    protected $pk = 'cat_id';

}
