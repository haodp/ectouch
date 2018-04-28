<?php

namespace app\http\controller;

/**
 * Class PmController
 * @package app\http\controller
 */
class PmController extends InitController
{
    public function actionIndex()
    {
        if (empty(session('user_id')) || $GLOBALS['_CFG']['integrate_code'] == 'ecshop') {
            return $this->redirect('./');
        }

        uc_call("uc_pm_location", [session('user_id')]);
    }
}
