<?php

namespace app\models;

use dao\Model;

/**
 * Class Agency
 * @package app\models
 * @property $agency_name
 * @property $agency_desc
 */
class Agency extends Model
{
    protected $table = 'agency';

    protected $pk = 'agency_id';

}
