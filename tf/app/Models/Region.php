<?php

namespace app\models;

use dao\Model;

/**
 * Class Region
 * @package app\models
 * @property $parent_id
 * @property $region_name
 * @property $region_type
 * @property $agency_id
 */
class Region extends Model
{
    protected $table = 'region';

    protected $pk = 'region_id';

}
