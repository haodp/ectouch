<?php

namespace app\models;

use dao\Model;

/**
 * Class Keywords
 * @package app\models
 * @property $date
 * @property $searchengine
 * @property $keyword
 * @property $count
 */
class Keywords extends Model
{
    protected $table = 'keywords';

}
