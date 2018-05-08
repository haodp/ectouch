<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Keywords
 * @package App\Models
 * @property $date
 * @property $searchengine
 * @property $keyword
 * @property $count
 */
class Keywords extends Model
{
    protected $table = 'keywords';

}
