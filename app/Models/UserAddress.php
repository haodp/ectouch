<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserAddress
 * @package App\Models
 * @property $address_name
 * @property $user_id
 * @property $consignee
 * @property $email
 * @property $country
 * @property $province
 * @property $city
 * @property $district
 * @property $address
 * @property $zipcode
 * @property $tel
 * @property $mobile
 * @property $sign_building
 * @property $best_time
 */
class UserAddress extends Model
{
    protected $table = 'user_address';

    protected $pk = 'address_id';

}
