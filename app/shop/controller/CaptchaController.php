<?php

namespace app\shop\controller;

use app\extensions\Captcha;

/**
 * 生成验证码
 * Class CaptchaController
 * @package app\shop\controller
 */
class CaptchaController extends InitController
{
    public function index()
    {
        $img = new Captcha(public_path('data/captcha/'), $GLOBALS['_CFG']['captcha_width'], $GLOBALS['_CFG']['captcha_height']);
        @ob_end_clean(); //清除之前出现的多余输入
        if (isset($_REQUEST['is_login'])) {
            $img->session_word = 'captcha_login';
        }
        $img->generate_image();
    }
}
