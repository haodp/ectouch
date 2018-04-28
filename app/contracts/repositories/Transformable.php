<?php

namespace app\contracts\repositories;

/**
 * Interface Transformable
 * @package app\contracts\repositories
 */
interface Transformable
{
    /**
     * @return array
     */
    public function transform();
}
