<?php

namespace RotHub\PHP\Services\Snowflake;

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
    protected $key = 'snowflake';

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
    public function next(int $time): int
    {
        $lasttime = $this->redis->hGet($this->key, 'lasttime');

        if ($lasttime > $time) {
            Error::fail('The time can\'t be greater than last time.');
        } else if ($lasttime == $time) {
            $sequence = $this->redis->hIncrBy($this->key, 'sequence', 1);
        } else {
            $this->redis->hSet($this->key, 'lasttime', $time);
            $sequence = $this->redis->hSet($this->key, 'sequence', 0);
        }

        return $sequence;
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
