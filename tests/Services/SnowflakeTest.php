<?php

use RotHub\PHP\Facades\Snowflake;
use RotHub\PHP\Services\Snowflake\RedisSequence;
use RotHub\PHP\Tests\TestCase;

class SnowflakeTest extends TestCase
{
    /**
     * @test
     */
    public function testRedis()
    {
        $sequence = new RedisSequence($this->redis());

        $id = Snowflake::fake($this->config())
            ->setSequence($sequence)
            ->id();

        $this->assertGreaterThan(0, $id);
    }

    /**
     * @test
     */
    public function testId()
    {
        $id = Snowflake::fake($this->config())->id();

        $this->assertGreaterThan(0, $id);
    }

    /**
     * @test
     */
    public function testParseId()
    {
        $id = '128101585556201505';

        $res = Snowflake::fake($this->config())->parseId($id);

        $this->assertArrayHasKey('timestamp', $res);
        $this->assertArrayHasKey('datacenter', $res);
        $this->assertArrayHasKey('workerid', $res);
        $this->assertArrayHasKey('sequence', $res);
    }

    protected function redis()
    {
        $redis = new \Redis();
        $redis->connect(constant('REDIS_HOST'), constant('REDIS_PORT'));
        $redis->auth(constant('REDIS_AUTH'));
        $redis->select(constant('REDIS_DB'));

        return $redis;
    }

    protected function config()
    {
        return [
            'datacenter' => 30,
            'workerid' => 30,
        ];
    }
}
