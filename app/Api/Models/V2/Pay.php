<?php

namespace App\Api\Models\V2;

use App\Api\Models\BaseModel;

class Pay extends BaseModel
{

    protected $table = 'payment';

    public $timestamps = false;

    public static function checkConfig($pay_code)
    {
        // $sql = "SELECT * FROM " . $ecs->table('payment') . " WHERE pay_code = '$_REQUEST[code]' AND enabled = '1'";
        if ($payment = self::where('pay_code', $pay_code)->where('enabled', '1')->first()) {
            return true;
        }

        return false;
    }
}