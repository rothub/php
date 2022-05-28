<?php

namespace RotHub\PHP\Providers;

use RotHub\PHP\Client;
use RotHub\PHP\Support\Collection;
use RotHub\PHP\Providers\AbstractProvider;

class ConfigProvider extends AbstractProvider
{
    /**
     * @inheritdoc
     */
    public static function name(): string
    {
        return 'Config';
    }

    /**
     * @inheritdoc
     */
    protected function concrete(Client $app): mixed
    {
        return new Collection($app->config());
    }
}
