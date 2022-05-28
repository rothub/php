<?php

use RotHub\PHP\Facades\Nimble;
use RotHub\PHP\Tests\TestCase;

class NimbleTest extends TestCase
{
    /**
     * @test
     */
    public function testGroup()
    {
        $data = $this->array();

        $res = Nimble::array($data)->group('name');
        $this->assertSame([
            'A' => [['id' => 1, 'name' => 'A']],
            'B' => [['id' => 2, 'name' => 'B']],
            'C' => [
                ['id' => 3, 'name' => 'C'],
                ['id' => 4, 'name' => 'C'],
            ],
        ], $res);
    }

    /**
     * @test
     */
    public function testSortByArray()
    {
        $data = $this->array();

        $sort = [3, 4, 2, 1];
        $res = Nimble::array($data)->sortByArray($sort, 'id');
        $this->assertSame([
            ['id' => 3, 'name' => 'C'],
            ['id' => 4, 'name' => 'C'],
            ['id' => 2, 'name' => 'B'],
            ['id' => 1, 'name' => 'A'],
        ], $res);
    }

    protected function array()
    {
        return [
            ['id' => 1, 'name' => 'A'],
            ['id' => 2, 'name' => 'B'],
            ['id' => 3, 'name' => 'C'],
            ['id' => 4, 'name' => 'C'],
        ];
    }

    protected function string()
    {
        return 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    }
}
