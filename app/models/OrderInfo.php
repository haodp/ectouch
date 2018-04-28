<?php

namespace app\models;

use think\Model;

/**
 * Class OrderInfo
 * @package app\models
 * @property $order_sn
 * @property $user_id
 * @property $order_status
 * @property $shipping_status
 * @property $pay_status
 * @property $consignee
 * @property $country
 * @property $province
 * @property $city
 * @property $district
 * @property $address
 * @property $zipcode
 * @property $tel
 * @property $mobile
 * @property $email
 * @property $best_time
 * @property $sign_building
 * @property $postscript
 * @property $shipping_id
 * @property $shipping_name
 * @property $pay_id
 * @property $pay_name
 * @property $how_oos
 * @property $how_surplus
 * @property $pack_name
 * @property $card_name
 * @property $card_message
 * @property $inv_payee
 * @property $inv_content
 * @property $goods_amount
 * @property $shipping_fee
 * @property $insure_fee
 * @property $pay_fee
 * @property $pack_fee
 * @property $card_fee
 * @property $money_paid
 * @property $surplus
 * @property $integral
 * @property $integral_money
 * @property $bonus
 * @property $order_amount
 * @property $from_ad
 * @property $referer
 * @property $add_time
 * @property $confirm_time
 * @property $pay_time
 * @property $shipping_time
 * @property $pack_id
 * @property $card_id
 * @property $bonus_id
 * @property $invoice_no
 * @property $extension_code
 * @property $extension_id
 * @property $to_buyer
 * @property $pay_note
 * @property $agency_id
 * @property $inv_type
 * @property $tax
 * @property $is_separate
 * @property $parent_id
 * @property $discount
 */
class OrderInfo extends Model
{
    protected $table = 'order_info';

    protected $pk = 'order_id';

}
