<?php

namespace App\Http\Controllers;

class AboutController extends InitController
{
    public function map() {
        $address = I('get.address', '');
        if(empty($address)){
        	$province = model('RegionBase')->get_region_name(C('SHOP_PROVINCE'));
        	$city = model('RegionBase')->get_region_name(C('SHOP_CITY'));
            $address = C('CFG.SHOP_ADDRESS');
        }
        $this->assign('city', $city);
        $this->assign('address', $city . $address);
        $this->display('about_map.dwt');
    }
}
