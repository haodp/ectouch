<?php

namespace app\models;

use think\Model;

/**
 * Class Ad
 * @package app\models
 * @property integer $ad_id 自增ID
 * @property integer $position_id 广告位ID
 * @property integer $media_type 广告类型
 * @property string $ad_name 广告名称
 * @property string $ad_link 广告链接
 * @property string $ad_code 广告代码
 * @property integer $start_time 开始时间
 * @property integer $end_time 结束时间
 * @property string $link_man 联系人
 * @property string $link_email 联系人邮箱
 * @property string $link_phone 联系人电话
 * @property integer $click_count 广告点击数
 * @property integer $enabled 是否启用
 */
class Ad extends Model
{
    protected $table = 'ad';

    protected $pk = 'ad_id';

}
