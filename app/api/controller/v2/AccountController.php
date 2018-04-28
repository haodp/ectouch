<?php

namespace app\api\controller\v2;

use app\api\controller\Controller;
use app\api\model\v2\UserAccount;
use app\api\classes\Token;
use app\api\model\v2\Account;

class AccountController extends Controller
{

    /**
    * POST ecapi.withdraw.list
    */
    public function actionIndex()
    {
        $rules = [
            'page' => 'integer|min:1',
            'per_page' => 'integer|min:1',
        ];
        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $model = UserAccount::getList($this->validated);
        return $this->json($model);
    }

    /**
    * POST ecapi.withdraw.info
    */
    public function getDetail()
    {
        $rules = [
            'id' => 'required|integer|min:1',
        ];
        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $model = UserAccount::getDetail($this->validated);
        return $this->json($model);
    }

    /**
    * POST ecapi.withdraw.submit
    */
    public function submit()
    {
        $rules = [
            "cash"   => 'required|numeric',    // 金额
            "memo"  => 'required|string',     // 备注
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $model = UserAccount::submit($this->validated);
        return $this->json($model);
    }

    /**
    * POST ecapi.withdraw.cancel
    */
    public function cancel()
    {
        $rules = [
            "id"   => 'required|integer',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $data = UserAccount::cancel($this->validated);
        return $this->json($data);
    }

    /**
     * POST ecapi.balance.total
     */
    public function surplus()
    {
        $user_id = Token::authorization();
        $data = UserAccount::getUserSurplus($user_id);
        return $this->json($data);
    }
    
    /**
     * POST ecapi.balance.list
     */
    public function accountDetail()
    {
        $rules = [
                'status'   => 'integer',           // 状态   全部  收入  支出
                'page'     => 'integer|min:1',     // 当前第几页
                'per_page' => 'integer|min:1',     // 每页多少
        ];
        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $data = Account::accountDetail($this->validated);
        return $this->json($data);
    }
}
