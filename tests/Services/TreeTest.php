<?php

use RotHub\PHP\Facades\Tree;
use RotHub\PHP\Tests\TestCase;

class TreeTest extends TestCase
{
    /**
     * @test
     */
    public function testBuild()
    {
        $data = [
            ['id' => 1, 'parent_id' => ''],
            ['id' => 2, 'parent_id' => ''],
            ['id' => 12, 'parent_id' => 1],
            ['id' => 121, 'parent_id' => 12],
            ['id' => 21, 'parent_id' => 2],
        ];

        $res = Tree::fake()
            ->setData($data)
            ->build();

        return $this->assertIsArray($res);
    }
}
