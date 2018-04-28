<?php

namespace app\models;

use think\Model;

/**
 * Class Pack
 * @package app\models
 * @property $pack_name
 * @property $pack_img
 * @property $pack_fee
 * @property $free_money
 * @property $pack_desc
 */
class Pack extends Model
{
    protected $table = 'pack';

    protected $pk = 'pack_id';

}
