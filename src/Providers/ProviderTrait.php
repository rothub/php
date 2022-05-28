<?php

namespace RotHub\PHP\Providers;

trait ProviderTrait
{
    /**
     * @var array 服务提供者.
     */
    protected $providers = [
        \RotHub\PHP\Providers\BankCardProvider::class,
        \RotHub\PHP\Providers\CacheProvider::class,
        \RotHub\PHP\Providers\CalendarProvider::class,
        \RotHub\PHP\Providers\ConfigProvider::class,
        \RotHub\PHP\Providers\ExpressProvider::class,
        \RotHub\PHP\Providers\FileProvider::class,
        \RotHub\PHP\Providers\HttpProvider::class,
        \RotHub\PHP\Providers\MathProvider::class,
        \RotHub\PHP\Providers\NimbleProvider::class,
        \RotHub\PHP\Providers\RequestProvider::class,
        \RotHub\PHP\Providers\ResponseProvider::class,
        \RotHub\PHP\Providers\SensitiveProvider::class,
        \RotHub\PHP\Providers\SequenceProvider::class,
        \RotHub\PHP\Providers\SnowflakeProvider::class,
        \RotHub\PHP\Providers\TreeProvider::class,
        \RotHub\PHP\Providers\WeChatPayProvider::class,
        \RotHub\PHP\Providers\WeChatProvider::class,
        \RotHub\PHP\Providers\WebHookProvider::class,
    ];
}
