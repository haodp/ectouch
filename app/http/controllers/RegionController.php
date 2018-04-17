<?php

namespace app\http\controllers;

use app\extensions\Json;

/**
 * Class RegionController
 * @package app\http\controllers
 */
class RegionController extends InitController
{
    public function actionIndex()
    {
        header('Content-type: text/html; charset=' . CHARSET);

        $type = !empty($_REQUEST['type']) ? intval($_REQUEST['type']) : 0;
        $parent = !empty($_REQUEST['parent']) ? intval($_REQUEST['parent']) : 0;

        $arr['regions'] = get_regions($type, $parent);
        $arr['type'] = $type;
        $arr['target'] = !empty($_REQUEST['target']) ? stripslashes(trim($_REQUEST['target'])) : '';
        $arr['target'] = htmlspecialchars($arr['target']);

        $json = new Json();
        echo $json->encode($arr);
    }
}