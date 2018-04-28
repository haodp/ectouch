<?php

namespace app\models;

use think\Model;

/**
 * Class BackOrder
 * @package app\models
 * @property $delivery_sn
 * @property $order_sn
 * @property $order_id
 * @property $invoice_no
 * @property $add_time
 * @property $shipping_id
 * @property $shipping_name
 * @property $user_id
 * @property $action_user
 * @property $consignee
 * @property $address
 * @property $country
 * @property $province
 * @property $city
 * @property $district
 * @property $sign_building
 * @property $email
 * @property $zipcode
 * @property $tel
 * @property $mobile
 * @property $best_time
 * @property $postscript
 * @property $how_oos
 * @property $insure_fee
 * @property $shipping_fee
 * @property $update_time
 * @property $suppliers_id
 * @property $status
 * @property $return_time
 * @property $agency_id
 */
class BackOrder extends Model
{
    protected $table = 'back_order';

    protected $pk = 'back_id';

}
