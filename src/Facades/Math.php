<?php

namespace RotHub\PHP\Facades;

/**
 * @method static string add(string $num1, string $num2, int $scale = 2)
 * @method static string sub(string $num1, string $num2, int $scale = 2)
 * @method static string mul(string $num1, string $num2, int $scale = 2)
 * @method static string div(string $num1, string $num2, int $scale = 2)
 * @method static string percent(string $num1, string $num2, int $scale = 2)
 *
 * @see \RotHub\PHP\Services\Math\Client
 */
class Math extends \RotHub\PHP\Facades\AbstractFacade
{
    /**
     * @inheritdoc
     */
    protected $class = \RotHub\PHP\Providers\MathProvider::class;
}
