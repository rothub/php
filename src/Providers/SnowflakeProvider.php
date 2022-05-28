<?php

namespace RotHub\PHP\Providers;

use RotHub\PHP\Providers\AbstractProvider;

class SnowflakeProvider extends AbstractProvider
{
    /**
     * @inheritdoc
     */
    public static function name(): string
    {
        return 'Snowflake';
    }
}
