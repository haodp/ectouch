<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%back_order}}".
 *
 * @property int $back_id
 * @property string $delivery_sn
 * @property string $order_sn
 * @property int $order_id
 * @property string $invoice_no
 * @property int $add_time
 * @property int $shipping_id
 * @property string $shipping_name
 * @property int $user_id
 * @property string $action_user
 * @property string $consignee
 * @property string $address
 * @property int $country
 * @property int $province
 * @property int $city
 * @property int $district
 * @property string $sign_building
 * @property string $email
 * @property string $zipcode
 * @property string $tel
 * @property string $mobile
 * @property string $best_time
 * @property string $postscript
 * @property string $how_oos
 * @property string $insure_fee
 * @property string $shipping_fee
 * @property int $update_time
 * @property int $suppliers_id
 * @property int $status
 * @property int $return_time
 * @property int $agency_id
 */
class BackOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%back_order}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['delivery_sn', 'order_sn'], 'required'],
            [['order_id', 'add_time', 'user_id', 'country', 'province', 'city', 'district', 'update_time', 'suppliers_id', 'return_time', 'agency_id'], 'integer'],
            [['insure_fee', 'shipping_fee'], 'number'],
            [['delivery_sn', 'order_sn'], 'string', 'max' => 20],
            [['invoice_no'], 'string', 'max' => 50],
            [['shipping_id'], 'string', 'max' => 3],
            [['shipping_name', 'sign_building', 'best_time', 'how_oos'], 'string', 'max' => 120],
            [['action_user'], 'string', 'max' => 30],
            [['consignee', 'email', 'zipcode', 'tel', 'mobile'], 'string', 'max' => 60],
            [['address'], 'string', 'max' => 250],
            [['postscript'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'back_id' => 'Back ID',
            'delivery_sn' => 'Delivery Sn',
            'order_sn' => 'Order Sn',
            'order_id' => 'Order ID',
            'invoice_no' => 'Invoice No',
            'add_time' => 'Add Time',
            'shipping_id' => 'Shipping ID',
            'shipping_name' => 'Shipping Name',
            'user_id' => 'User ID',
            'action_user' => 'Action User',
            'consignee' => 'Consignee',
            'address' => 'Address',
            'country' => 'Country',
            'province' => 'Province',
            'city' => 'City',
            'district' => 'District',
            'sign_building' => 'Sign Building',
            'email' => 'Email',
            'zipcode' => 'Zipcode',
            'tel' => 'Tel',
            'mobile' => 'Mobile',
            'best_time' => 'Best Time',
            'postscript' => 'Postscript',
            'how_oos' => 'How Oos',
            'insure_fee' => 'Insure Fee',
            'shipping_fee' => 'Shipping Fee',
            'update_time' => 'Update Time',
            'suppliers_id' => 'Suppliers ID',
            'status' => 'Status',
            'return_time' => 'Return Time',
            'agency_id' => 'Agency ID',
        ];
    }
}
