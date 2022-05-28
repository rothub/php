<?php

namespace RotHub\PHP\Services\Snowflake;

interface SequenceInterface
{
    /**
     * 下一个序列号.
     *
     * @param int $time 时间.
     * @return int
     */
    public function next(int $time): int;
}
