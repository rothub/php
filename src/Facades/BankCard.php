<?php

namespace RotHub\PHP\Facades;

/**
 * @method static array banks()
 * @method static string logo(string $bankNo)
 * @method static array card(string $cardNo)
 *
 * @see \RotHub\PHP\Services\BankCard\Client
 */
class BankCard extends \RotHub\PHP\Facades\AbstractFacade
{
    /**
     * @inheritdoc
     */
    protected $class = \RotHub\PHP\Providers\BankCardProvider::class;
}
