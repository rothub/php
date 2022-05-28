<?php

namespace RotHub\PHP\Facades;

/**
 * @method string id()
 * @method array parseId(string $id, bool $bin = false)
 * @method int startMicrotime()
 * @method static setStartMicrotime(int $microtime)
 * @method RotHub\PHP\Services\Snowflake\SequenceInterface|callable sequence()
 * @method static setSequence(RotHub\PHP\Services\Snowflake\SequenceInterface|callable $sequence)
 *
 * @see \RotHub\PHP\Services\Snowflake\Client
 */
class Snowflake extends \RotHub\PHP\Facades\AbstractFacade
{
    /**
     * @inheritdoc
     */
    protected $class = \RotHub\PHP\Providers\SnowflakeProvider::class;
}
