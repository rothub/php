<?php

namespace RotHub\PHP\Services\Sequence;

use RotHub\PHP\Exceptions\Error;

class RedisSequence implements SequenceInterface
{
    /**
     * @var \Redis Redis 实例.
     */
    protected $redis;
    /**
     * @var string 键名.
     */
    protected $key = 'sequence';

    /**
     * 构造函数.
     *
     * @param Redis $redis Redis 客户端.
     */
    public function __construct(\Redis $redis)
    {
        if ($redis->ping('ping')) {
            $this->redis = $redis;

            return;
        }

        Error::fail('Redis server went away.');
    }

    /**
     * @inheritdoc
     */
    public function next(int $min = 0, int $step = 1): int
    {
        if ($this->redis->exists($this->key)) {
            return $this->redis->incrBy($this->key, $step);
        }

        $this->redis->set($this->key, $min);

        return $min;
    }

    /**
     * 设置键名.
     *
     * @param string $key 键名.
     * @return static
     */
    public function setKey(string $key): static
    {
        $this->key = $key;

        return $this;
    }
}
