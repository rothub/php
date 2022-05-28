<?php

namespace RotHub\PHP\Middlewares;

use RotHub\PHP\Client;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractMiddleware
{
    /**
     * @var Client 配置.
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
     * @param Client|null $app 程序.
     * @param array $config 配置.
     * @return void
     */
    public function __construct(?Client $app, array $config = [])
    {
        $this->app = $app;

        $this->setConfig($config);
        $this->init();
    }

    /**
     * 工厂.
     * 
     * @param Client|null $app 程序.
     * @param array $config 配置.
     * @return static
     */
    public static function fake(?Client $app, array $config = []): static
    {
        return new static($app, $config);
    }

    /**
     * 生成.
     *
     * @return callable
     */
    public function build(): callable
    {
        return function (callable $handler) {
            return function ($request, $options) use ($handler) {
                $this->request($request, $options);
                return $handler($request, $options)
                    ->then(function ($response) use ($request) {
                        $this->response($request, $response);
                        return $response;
                    });
            };
        };
    }

    /**
     * 初始化.
     * 
     * @return void
     */
    protected function init(): void
    {
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
     * 请求前.
     *
     * @param RequestInterface $request 请求.
     * @param array $options 配置.
     * @return void
     */
    protected function request(
        RequestInterface &$request,
        array &$options
    ): void {
    }

    /**
     * 请求后.
     *
     * @param RequestInterface $request 请求.
     * @param ResponseInterface $response 响应.
     * @return void
     */
    protected function response(
        RequestInterface &$request,
        ResponseInterface &$response
    ): void {
    }
}
