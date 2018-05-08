<?php

namespace app\api\controller\v2;

use app\api\controller\Controller;
use app\api\model\v2\Version;

class VersionController extends Controller
{
    /**
     * POST ecapi.version.check
     */
    public function check()
    {
        $data = Version::checkVersion();
        return $this->json($data);
    }
}
