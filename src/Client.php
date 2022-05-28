<?php

namespace RotHub\PHP;

use RotHub\PHP\Contracts\Client as ContractsClient;
use RotHub\PHP\Providers\CacheProvider;
use RotHub\PHP\Providers\ConfigProvider;
use RotHub\PHP\Providers\HttpProvider;
use RotHub\PHP\Providers\ProviderTrait;
use RotHub\PHP\Providers\RequestProvider;
use RotHub\PHP\Providers\ResponseProvider;

class Client extends \Pimple\Container implements ContractsClient
{
    use ProviderTrait;

    /**
     * @var array 配置.
     */
    protected $config = [];

    /**
     * 构造函数.
     *
     * @param array $config 配置.
     * @param array $providers 服务.
     * @return void
     */
    public function __construct(array $config = [], array $providers = null)
    {
        $this->config = $config;
        is_null($providers) or $this->providers = $providers;

        $this->binds($this->providers());
    }

    /**
     * @inheritdoc
     */
    public static function fake(array $config = [], array $providers = null): static
    {
        return new static($config, $providers);
    }

    /**
     * @inheritdoc
     */
    public static function sole(string $class, array $config = []): mixed
    {
        $name = $class::name();
        $app = new static([$name => $config], [$class]);

        return $app[$name];
    }

    /**
     * @inheritdoc
     */
    public static function name(): string
    {
        return 'Rot';
    }

    /**
     * @inheritdoc
     */
    public function config(): array
    {
        return $this->config;
    }

    /**
     * @inheritdoc
     */
    public function providers(): array
    {
        return array_merge([
            CacheProvider::class,
            ConfigProvider::class,
            HttpProvider::class,
            RequestProvider::class,
            ResponseProvider::class,
        ], $this->providers);
    }

    /**
     * @inheritdoc
     */
    public function binds(array $providers): void
    {
        foreach ($providers as $provider) {
            parent::register(new $provider());
        }
    }

    /**
     * @inheritdoc
     */
    public function rebind(string $id, mixed $value): void
    {
        $this->offsetUnset($id);
        $this->offsetSet($id, $value);
    }
}
