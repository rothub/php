<?php

namespace RotHub\PHP\Services\Snowflake;

use RotHub\PHP\Services\Snowflake\SequenceInterface;

class RandomSequence implements SequenceInterface
{
    /**
     * @var int 时间.
     */
    protected $time = -1;
    /**
     * @var int 序列号.
     */
    protected $sequence = 0;

    /**
     * @inheritdoc
     */
    public function next(int $time): int
    {
        if ($this->time === $time) {
            ++$this->sequence;
        } else {
            $this->sequence = 0;
        }

        $this->time = $time;

        return $this->sequence;
    }
}
