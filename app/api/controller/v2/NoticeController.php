<?php

namespace app\api\controller\v2;

use app\api\controller\Controller;
use app\api\model\v2\Card;
use app\api\model\v2\Notice;

class NoticeController extends Controller
{

    /**
    * POST ecapi.notice.list
    */
    public function actionIndex()
    {
        $rules = [
            'page'      => 'required|integer|min:1',
            'per_page'  => 'required|integer|min:1',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $model = Notice::getList($this->validated);

        return $this->json($model);
    }

    /**
    * GET notice.{id:[0-9]+}
    */
    public function show($id)
    {
        return Notice::getNotice($id);
    }
}
