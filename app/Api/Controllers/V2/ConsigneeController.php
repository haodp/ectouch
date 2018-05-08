<?php

namespace App\Api\Controllers\V2;

use app\api\controller\Controller;
use app\api\model\v2\UserAddress;
use app\api\model\v2\Features;
use app\api\classes\Token;

class ConsigneeController extends Controller
{

    /**
    * POST ecapi.consignee.list
    */
    public function index()
    {
        $data = UserAddress::getList($this->validated);
        return $this->json($data);
    }

    /**
    * POST ecapi.consignee.add
    */
    public function add()
    {
        $rules = [
            'name'      => 'required|string|min:2|max:15',
            'mobile'    => 'numeric|required_without:tel',
            'tel'       => 'string|required_without:mobile',
            'zip_code'  => 'numeric',
            'region'    => 'required|integer|min:1',
            'address'   => 'required|string',
            'identity'  => 'string|min:2|max:19',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }
        $data = UserAddress::add($this->validated);

        return $this->json($data);
    }

    /**
    * POST ecapi.consignee.delete
    */
    public function remove()
    {
        $rules = [
            'consignee' => 'required|integer|min:1',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $data = UserAddress::remove($this->validated);
        return $this->json($data);
    }

    /**
    * POST ecapi.consignee.update
    */
    public function modify()
    {
        $rules = [
            'consignee' => 'required|integer|min:1',
            'name'      => 'required|string|min:2|max:15',
            'mobile'    => 'numeric|required_without:tel',
            'tel'       => 'string|required_without:mobile',
            'zip_code'  => 'numeric',
            'region'    => 'required|integer|min:1',
            'address'   => 'required|string',
            'identity'  => 'string|min:2|max:19',
        ];


        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $data = UserAddress::modify($this->validated);

        return $this->json($data);
    }

    /**
    * POST ecapi.consignee.setDefault
    */
    public function setDefault()
    {
        $rules = [
            'consignee' => 'required|integer|min:1',
        ];

        if ($res = Features::check('address.default')) {
            return $this->json($res);
        }

        if ($error = $this->validateInput($rules)) {
            return $error;
        }



        $data = UserAddress::setDefault($this->validated);

        return $this->json($data);
    }
}
