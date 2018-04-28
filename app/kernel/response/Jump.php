<?php

namespace app\kernel\response;

use app\kernel\Container;
use app\kernel\Response;

class Jump extends Response
{
    protected $contentType = 'text/html';

    /**
     * 处理数据
     * @access protected
     * @param  mixed $data 要处理的数据
     * @return mixed
     * @throws \Exception
     */
    protected function output($data)
    {
        $config = Container::get('config');
        $data   = Container::get('view')
            ->init($config->pull('template'))
            ->fetch($this->options['jump_template'], $data);
        return $data;
    }
}
