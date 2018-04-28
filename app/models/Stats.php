<?php

namespace app\models;

use think\Model;

/**
 * Class Stats
 * @package app\models
 * @property $access_time
 * @property $ip_address
 * @property $visit_times
 * @property $browser
 * @property $system
 * @property $language
 * @property $area
 * @property $referer_domain
 * @property $referer_path
 * @property $access_url
 */
class Stats extends Model
{
    protected $table = 'stats';

}
