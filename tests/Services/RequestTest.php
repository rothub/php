<?php

use RotHub\PHP\Facades\Request;
use RotHub\PHP\Tests\TestCase;

class RequestTest extends TestCase
{
    /**
     * @test
     */
    public function testRequest()
    {
        $scheme = Request::fake()->getScheme();

        $this->assertSame('http', $scheme);
    }
}
