<?php

namespace app\models;

use dao\Model;

/**
 * Class Plugins
 * @package app\models
 * @property $version
 * @property $library
 * @property $assign
 * @property $install_date
 */
class Plugins extends Model
{
    protected $table = 'plugins';

    protected $pk = 'code';

}
