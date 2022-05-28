<?php

namespace RotHub\PHP\Providers;

use GuzzleHttp\Client as HttpClient;
use RotHub\PHP\Client;
use RotHub\PHP\Providers\AbstractProvider;

class HttpProvider extends AbstractProvider
{
    /**
     * @inheritdoc
     */
    public static function name(): string
    {
        return 'Http';
    }

    /**
     * @inheritdoc
     */
    protected function concrete(Client $app): mixed
    {
        return new HttpClient($this->config($app));
    }
}
