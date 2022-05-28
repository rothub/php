<?php

namespace RotHub\PHP\Providers;

use RotHub\PHP\Providers\AbstractProvider;

class SequenceProvider extends AbstractProvider
{
    /**
     * @inheritdoc
     */
    public static function name(): string
    {
        return 'Sequence';
    }
}
