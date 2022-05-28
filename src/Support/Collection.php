<?php

namespace RotHub\PHP\Support;

class Collection implements \ArrayAccess
{
    /**
     * @var array 集合.
     */
    protected $items;

    /**
     * 构造函数.
     *
     * @param array $items 数据.
     * @return void
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * 得到.
     *
     * @param string $key 键名.
     * @param mixed $default 默认值.
     * @return mixed
     */
    public function get(string $key, $default = null): mixed
    {
        $items = $this->items;

        if (is_null($key)) {
            return $items;
        }

        if (isset($items[$key])) {
            return $items[$key];
        }

        foreach (explode('.', $key) as $item) {
            if (
                !is_array($items)
                || !array_key_exists($item, $items)
            ) {
                return $default;
            }

            $items = $items[$item];
        }

        return $items;
    }

    /**
     * 设置.
     *
     * @param string $key 键名.
     * @param mixed $value 值.
     * @return void
     */
    public function set(string $key, $value): void
    {
        $keys = explode('.', $key);
        $items = &$this->items;

        while (count($keys) > 1) {
            $key = array_shift($keys);

            if (
                !isset($items[$key])
                || !is_array($items[$key])
            ) {
                $items[$key] = [];
            }

            $items = &$items[$key];
        }

        $items[array_shift($keys)] = $value;
    }

    /**
     * 是否存在.
     *
     * @param string $key 键名.
     * @return bool
     */
    public function has(string $key): bool
    {
        return (bool) $this->get($key);
    }

    /**
     * @inheritdoc
     */
    public function offsetExists($offset): bool
    {
        return $this->has($offset);
    }

    /**
     * @inheritdoc
     */
    public function offsetGet($offset): mixed
    {
        return $this->get($offset);
    }

    /**
     * @inheritdoc
     */
    public function offsetSet($offset, $value): void
    {
        $this->set($offset, $value);
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset($offset): void
    {
        $this->set($offset, null);
    }
}
