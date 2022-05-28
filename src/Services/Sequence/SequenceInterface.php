<?php

namespace RotHub\PHP\Services\Sequence;

interface SequenceInterface
{
    /**
     * 下一个序列号.
     *
     * @return int
     */
    public function next(int $min = 0, int $step = 1): int;
}
