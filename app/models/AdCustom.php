<?php

namespace app\models;

use think\Model;

/**
 * Class AdCustom
 * @package app\models
 * @property integer $ad_id 自增ID
 * @property integer $ad_type 广告类型
 * @property string $ad_name 广告名称
 * @property string $add_time 添加时间
 * @property string $content 广告内容
 * @property string $url 广告链接
 * @property integer $ad_status 广告状态
 */
class AdCustom extends Model
{
    protected $table = 'ad_custom';

    protected $pk = 'ad_id';

}
