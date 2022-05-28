<?php

namespace RotHub\PHP\Facades;

/**
 * @method static RotHub\PHP\Services\Nimble\Arr array(array $data)
 * @method static RotHub\PHP\Services\Nimble\Str string(string $str)
 *
 * @see \RotHub\PHP\Services\Nimble\Client
 */
class Nimble extends \RotHub\PHP\Facades\AbstractFacade
{
    /**
     * @inheritdoc
     */
    protected $class = \RotHub\PHP\Providers\NimbleProvider::class;
}
