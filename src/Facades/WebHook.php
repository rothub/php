<?php

namespace RotHub\PHP\Facades;

/**
 * @method static setRequest(Symfony\Component\HttpFoundation\Request $request)
 * @method bool checkSign()
 * @method bool checkRepository()
 * @method array exec()
 * @method void log(mixed $data)
 * @method bool run()
 *
 * @see \RotHub\PHP\Services\WebHook\Client
 */
class WebHook extends \RotHub\PHP\Facades\AbstractFacade
{
    /**
     * @inheritdoc
     */
    protected $class = \RotHub\PHP\Providers\WebHookProvider::class;
}
