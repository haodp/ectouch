<?php

namespace app\models;

use dao\Model;

/**
 * Class EmailList
 * @package app\models
 * @property $email
 * @property $stat
 * @property $hash
 */
class EmailList extends Model
{
    protected $table = 'email_list';

}