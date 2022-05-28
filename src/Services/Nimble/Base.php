<?php

namespace RotHub\PHP\Services\Nimble;

class Base
{
    /**
     * @var mixed 原数据.
     */
    protected $raw;

    /**
     * @inheritdoc
     */
    public function __construct(mixed $data)
    {
        $this->setRaw($data);
    }

    /**
     * 设置原数据.
     *
     * @param mixed $raw 原数据.
     * @return static
     */
    public function setRaw(mixed $raw): static
    {
        $this->raw = $raw;

        return $this;
    }
}
