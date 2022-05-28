<?php

namespace RotHub\PHP\Contracts;

use Pimple\ServiceProviderInterface;

interface Provider extends ServiceProviderInterface
{
    /**
     * 名称.
     *
     * @return string
     */
    public static function name(): string;
}
