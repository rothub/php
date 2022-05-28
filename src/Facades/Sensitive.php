<?php

namespace RotHub\PHP\Facades;

/**
 * @method array readfile(string $filename, string $separator = ",")
 * @method void filltrie(array $words)
 * @method void saveLexicon(string $filename)
 * @method void readLexicon(string $filename)
 * @method array search(string $str)
 * @method string replace(string $str, callable|string $to)
 * @method __destruct()
 *
 * @see \RotHub\PHP\Services\Sensitive\Client
 */
class Sensitive extends \RotHub\PHP\Facades\AbstractFacade
{
    /**
     * @inheritdoc
     */
    protected $class = \RotHub\PHP\Providers\SensitiveProvider::class;
}
