<?php

namespace app\kernel\console\command\make;

use app\kernel\console\command\Make;

class Middleware extends Make
{
    protected $type = "Middleware";

    protected function configure()
    {
        parent::configure();
        $this->setName('make:middleware')
            ->setDescription('Create a new middleware class');
    }

    protected function getStub()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'middleware.stub';
    }

    protected function getNamespace($appNamespace, $module)
    {
        return parent::getNamespace($appNamespace, 'http') . '\middleware';
    }
}
