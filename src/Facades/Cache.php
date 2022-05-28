<?php

namespace RotHub\PHP\Facades;

/**
 * @method get($key, $default = null)
 * @method set($key, $value, $ttl = null)
 * @method delete($key)
 * @method clear()
 * @method getMultiple($keys, $default = null)
 * @method setMultiple($values, $ttl = null)
 * @method deleteMultiple($keys)
 * @method has($key)
 * @method prune()
 * @method reset()
 *
 * @see \Symfony\Component\Cache\Psr16Cache
 */
class Cache extends \RotHub\PHP\Facades\AbstractFacade
{
    /**
     * @inheritdoc
     */
    protected $class = \RotHub\PHP\Providers\CacheProvider::class;
}
