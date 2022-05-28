<?php

use RotHub\PHP\Facades\Config;
use RotHub\PHP\Tests\TestCase;

class ConfigTest extends TestCase
{
    /**
     * @test
     */
    public function testGet()
    {
        $value = Config::fake([
            'key1' => 'value1',
            'key2' => [
                'key2-1' => 'value2-1',
                'key2-2' => 'value2-2',
            ],
        ])->get('Config.key2.key2-1');

        $this->assertSame('value2-1', $value);
    }
}
