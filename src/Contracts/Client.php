<?php

namespace RotHub\PHP\Contracts;

interface Client
{
    /**
     * 工厂.
     *
     * @param array $config 配置.
     * @param array $providers 服务.
     * @return static
     */
    public static function fake(array $config = [], array $providers = null): static;

    /**
     * 单个服务.
     *
     * @param string $class 服务.
     * @param array $config 配置.
     * @return mixed
     */
    public static function sole(string $class, array $config = []): mixed;

    /**
     * 名称.
     *
     * @return string
     */
    public static function name(): string;

    /**
     * 配置.
     *
     * @return array
     */
    public function config(): array;

    /**
     * 服务.
     *
     * @return array
     */
    public function providers(): array;

    /**
     * 批量注册服务.
     *
     * @param array $providers 服务.
     * @return void
     */
    public function binds(array $providers): void;

    /**
     * 重新绑定服务.
     *
     * @param string $id 唯一标识.
     * @param mixed  $value 值.
     * @return void
     */
    public function rebind(string $id, mixed $value): void;
}
