<?php

namespace RotHub\PHP\Facades;

/**
 * @method array kdniao(string $url, string $type, array $params)
 *
 * @see \RotHub\PHP\Services\Express\Client
 */
class Express extends \RotHub\PHP\Facades\AbstractFacade
{
    /**
     * @inheritdoc
     */
    protected $class = \RotHub\PHP\Providers\ExpressProvider::class;
}
