<?php

namespace App\Http\Controllers;

/**
 * Class PmController
 * @package App\Http\Controllers
 */
class PmController extends InitController
{
    public function index()
    {
        if (empty(session('user_id')) || $GLOBALS['_CFG']['integrate_code'] == 'ecshop') {
            return $this->redirect('./');
        }

        uc_call("uc_pm_location", [session('user_id')]);
    }
}
