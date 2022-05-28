<?php

namespace RotHub\PHP\Providers;

use RotHub\PHP\Providers\AbstractProvider;

class WebHookProvider extends AbstractProvider
{
    /**
     * @inheritdoc
     */
    public static function name(): string
    {
        return 'WebHook';
    }
}
