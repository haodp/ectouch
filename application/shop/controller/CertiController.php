<?php

namespace app\shop\controller;

/**
 * 证书反查文件
 * Class CertiController
 * @package app\shop\controller
 */
class CertiController extends InitController
{
    public function index()
    {
        $session_id = empty($_POST['session_id']) ? '' : trim($_POST['session_id']);

        if (!empty($session_id)) {
            $sql = "SELECT sesskey FROM " . $this->ecs->table('sessions') . " WHERE sesskey = '" . $session_id . "' ";
            $sesskey = $this->db->getOne($sql);
            if ($sesskey != '') {
                exit('{"res":"succ","msg":"","info":""}');
            } else {
                exit('{"res":"fail","msg":"error:000002","info":""}');
            }
        } else {
            exit('{"res":"fail","msg":"error:000001","info":""}');
        }
    }
}