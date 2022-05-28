<?php

namespace RotHub\PHP\Facades;

/**
 * @method static array statute(int $year)
 *
 * @see \RotHub\PHP\Services\Calendar\Client
 */
class Calendar extends \RotHub\PHP\Facades\AbstractFacade
{
    /**
     * @inheritdoc
     */
    protected $class = \RotHub\PHP\Providers\CalendarProvider::class;
}
