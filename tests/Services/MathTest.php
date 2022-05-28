<?php

use RotHub\PHP\Facades\Math;
use RotHub\PHP\Tests\TestCase;

class MathTest extends TestCase
{
    /**
     * @test
     */
    public function testAdd()
    {
        $res = Math::add(1, 1);

        $this->assertSame('2.00', $res);
    }

    /**
     * @test
     */
    public function testSub()
    {
        $res = Math::sub(1, 1);

        $this->assertSame('0.00', $res);
    }

    /**
     * @test
     */
    public function testMul()
    {
        $res = Math::mul(2, 2);

        $this->assertSame('4.00', $res);
    }

    /**
     * @test
     */
    public function testDiv()
    {
        $res = Math::div(4, 2);

        $this->assertSame('2.00', $res);
    }

    /**
     * @test
     */
    public function testPercent()
    {
        $res = Math::percent(4, 2);

        $this->assertSame('200.00%', $res);
    }
}
