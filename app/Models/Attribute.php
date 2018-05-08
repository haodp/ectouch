<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Attribute
 * @package App\Models
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
