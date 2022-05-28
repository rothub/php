<?php

namespace RotHub\PHP\Facades;

/**
 * @method string id()
 * @method string encode()
 * @method RotHub\PHP\Services\Sequence\SequenceInterface|callable sequence()
 * @method static setSequence(RotHub\PHP\Services\Sequence\SequenceInterface|callable $sequence)
 *
 * @see \RotHub\PHP\Services\Sequence\Client
 */
class Sequence extends \RotHub\PHP\Facades\AbstractFacade
{
    /**
     * @inheritdoc
     */
    protected $class = \RotHub\PHP\Providers\SequenceProvider::class;
}
