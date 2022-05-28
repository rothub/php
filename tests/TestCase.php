<?php

namespace RotHub\PHP\Tests;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * 路径.
     *
     * @param string $dir 路径.
     * @return string
     */
    protected function dir(string $dir = ''): string
    {
        return __DIR__ . '/..' . $dir;
    }
}
