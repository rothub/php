<?php

use RotHub\PHP\Facades\Response;
use RotHub\PHP\Tests\TestCase;

class ResponseTest extends TestCase
{
    /**
     * @test
     */
    public function testResponse()
    {
        $res = Response::fake()
            ->create('success')
            ->getContent();

        $this->assertSame('success', $res);
    }
}
