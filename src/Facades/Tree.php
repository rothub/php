<?php

namespace RotHub\PHP\Facades;

/**
 * @method static setData(array $data)
 * @method static setTopValue(string $value)
 * @method static setIdField(string $value)
 * @method static setParentField(string $value)
 * @method static setChildrenField(string $value)
 * @method array build()
 *
 * @see \RotHub\PHP\Services\Tree\Client
 */
class Tree extends \RotHub\PHP\Facades\AbstractFacade
{
    /**
     * @inheritdoc
     */
    protected $class = \RotHub\PHP\Providers\TreeProvider::class;
}
