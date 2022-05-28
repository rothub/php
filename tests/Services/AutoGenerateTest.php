<?php

use RotHub\PHP\Generate\Facade;
use RotHub\PHP\Generate\Provider;
use RotHub\PHP\Tests\TestCase;

class AutoGenerateTest extends TestCase
{
    /**
     * @test
     */
    public function testProvider()
    {
        $name = '/src/Providers/ProviderTrait.php';
        (new Provider())
            ->setNameSpace('RotHub\PHP\Providers')
            ->setName($this->dir($name))
            ->setPath($this->dir('/src/Providers'))
            ->run();

        $this->assertFileExists($this->dir($name));
    }

    /**
     * @test
     */
    public function testFacade()
    {
        (new Facade())
            ->setNamespace('RotHub\PHP\Facades')
            ->setPath($this->dir('/src/Facades/'))
            ->clear()
            ->run();

        $this->assertFileExists($this->dir('/src/Facades/Http.php'));
    }
}
