<?php

namespace RotHub\PHP\Providers;

use RotHub\PHP\Providers\AbstractProvider;

class WeChatPayProvider extends AbstractProvider
{
    /**
     * @inheritdoc
     */
    public static function name(): string
    {
        return 'WeChatPay';
    }
}
