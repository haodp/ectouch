<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Card
 * @package App\Models
 * @property $card_name
 * @property $card_img
 * @property $card_fee
 * @property $free_money
 * @property $card_desc
 */
class Card extends Model
{
    protected $table = 'card';

    protected $pk = 'card_id';

}
