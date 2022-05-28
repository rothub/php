<?php

namespace RotHub\PHP\Providers;

use RotHub\PHP\Providers\AbstractProvider;

class ExpressProvider extends AbstractProvider
{
    /**
     * @inheritdoc
     */
    public static function name(): string
    {
        return 'Express';
    }
}
