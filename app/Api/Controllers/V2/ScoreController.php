<?php

namespace app\api\controller\v2;

use app\api\controller\Controller;
use app\api\model\v2\Member;
use app\api\model\v2\Features;
use app\api\model\v2\AccountLog;

class ScoreController extends Controller
{

    /**
    * POST ecapi.score.get
    */
    public function view()
    {
        if ($res = Features::check('score')) {
            return $this->json($res);
        }

        $model = Member::getUserPayPoints();
        return $this->json($model);
    }

    /**
    * POST ecapi.score.history.list
    */
    public function history()
    {
        if ($res = Features::check('score')) {
            return $this->json($res);
        }

        $rules = [
            'page'      => 'required|integer|min:1',
            'per_page'  => 'required|integer|min:1',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $model = AccountLog::getPayPointsList($this->validated);

        return $this->json($model);
    }
}
