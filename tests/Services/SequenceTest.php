<?php

use RotHub\PHP\Facades\Sequence;
use RotHub\PHP\Services\Sequence\RedisSequence;
use RotHub\PHP\Tests\TestCase;

class SequenceTest extends TestCase
{
    /**
     * @test
     */
    public function testId()
    {
        $sequence = new RedisSequence($this->redis());

        $id = Sequence::fake($this->config())
            ->setSequence($sequence)
            ->id();

        $this->assertGreaterThanOrEqual(0, $id);
    }

    /**
     * @test
     */
    public function testEncode()
    {
        $sequence = new RedisSequence($this->redis());

        $encode = Sequence::fake($this->config())
            ->setSequence($sequence)
            ->encode();

        $this->assertGreaterThan(0, $encode);
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
            'min' => 0,
            'max' => 999999999999,
            'step' => 1,
            'length' => 12,
            'prefix' => '',
            'suffix' => '',
        ];
    }
}
