<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Plugins
 * @package App\Models
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
