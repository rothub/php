<?php

namespace RotHub\PHP\Providers;

use RotHub\PHP\Client;
use RotHub\PHP\Providers\AbstractProvider;
use Symfony\Component\HttpFoundation\Response;

class ResponseProvider extends AbstractProvider
{
    /**
     * @inheritdoc
     */
    public static function name(): string
    {
        return 'Response';
    }

    /**
     * @inheritdoc
     */
    protected function concrete(Client $app): mixed
    {
        return new Response();
    }
}
