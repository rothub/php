<?php

namespace RotHub\PHP\Facades;

use RotHub\PHP\Client;
use RotHub\PHP\Exceptions\Error;

abstract class AbstractFacade
{
    /**
     * @var string 服务类名.
     */
    protected $class;

    /**
     * @var mixed 实例.
     */
    protected $instance;

    /**
     * @inheritdoc
     */
    private function __construct()
    {
    }

    /**
     * 工厂.
     * 
     * @param array $config 配置.
     * @return static
     */
    public static function fake(array $config = []): static
    {
        $fake = new static();

        if (!class_exists($fake->class)) {
            Error::fail('A facade has not been set.');
        }

        $fake->instance = Client::sole($fake->class, $config);

        return $fake;
    }

    /**
     * @inheritdoc
     */
    public function __call($name, $arguments)
    {
        return $this->instance->{$name}(...$arguments);
    }

    /**
     * @inheritdoc
     */
    public static function __callStatic($name, $arguments)
    {
        return static::fake()->{$name}(...$arguments);
    }
}
