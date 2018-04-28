<?php

namespace app\models;

use think\Model;

/**
 * Class Attribute
 * @package app\models
 * @property $cat_id
 * @property $attr_name
 * @property $attr_input_type
 * @property $attr_type
 * @property $attr_values
 * @property $attr_index
 * @property $sort_order
 * @property $is_linked
 * @property $attr_group
 */
class Attribute extends Model
{
    protected $table = 'attribute';

    protected $pk = 'attr_id';

}
