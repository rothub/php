<?php

namespace RotHub\PHP\Facades;

/**
 * @method static array files(string $path, string $pattern = "*")
 *
 * @see \RotHub\PHP\Services\File\Client
 */
class File extends \RotHub\PHP\Facades\AbstractFacade
{
    /**
     * @inheritdoc
     */
    protected $class = \RotHub\PHP\Providers\FileProvider::class;
}
