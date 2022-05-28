<?php

namespace RotHub\PHP\Services;

use RotHub\PHP\Client;

abstract class AbstractService
{
    /**
     * @var Client 程序.
     */
    protected $app;

    /**
     * @var array 默认配置.
     */
    protected $default = [];

    /**
     * @var array 配置.
     */
    protected $config = [];

    /**
     * 构造函数.
     * 
     * @param Client $app 程序.
     * @param array $config 配置.
     * @return void
     */
    public function __construct(Client $app, array $config)
    {
        $this->app = $app;

        $this->setConfig($config);
        $this->init();
    }

    /**
     * 工厂.
     * 
     * @param Client $app 程序.
     * @param array $config 配置.
     * @return static
     */
    public static function fake(Client $app, array $config = []): static
    {
        return new static($app, $config);
    }

    /**
     * 设置配置.
     * 
     * @param array $config 配置.
     * @return void
     */
    public function setConfig(array $config): void
    {
        $this->config = array_merge($this->default, $config);
    }

    /**
     * 初始化.
     * 
     * @return void
     */
    protected function init(): void
    {
    }
}
