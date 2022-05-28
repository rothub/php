<?php

use RotHub\PHP\Facades\Sensitive;
use RotHub\PHP\Tests\TestCase;

class SensitiveTest extends TestCase
{
    /**
     * @test
     */
    public function testSave()
    {
        $input = $this->dir('/tests/Sensitive/badword.txt');
        $output = $this->dir('/tests/Sensitive/badword.bin');

        $client = Sensitive::fake();
        $data = $client->readfile($input);
        $client->filltrie($data);
        $client->saveLexicon($output);

        $this->assertFileExists($output);
    }

    /**
     * @test
     */
    public function testSearch()
    {
        $lexicon = $this->dir('/tests/Sensitive/badword.bin');

        $client = Sensitive::fake();
        $client->readLexicon($lexicon);
        $res = $client->search('我要包二奶');

        $this->assertEquals($res, [
            '包二奶' => [
                'count' => 1,
                'value' => 1,
            ]
        ]);
    }

    /**
     * @test
     */
    public function testReplace()
    {
        $lexicon = $this->dir('/tests/Sensitive/badword.bin');

        $client = Sensitive::fake();
        $client->readLexicon($lexicon);
        $replaced = $client->replace('我要包二奶', '**');

        $this->assertEquals($replaced, '我要**');

        $replaced = $client->replace(
            '我要包二奶',
            function ($word, $value) {
                return "[$word -> $value]";
            }
        );

        $this->assertEquals($replaced, '我要[包二奶 -> 1]');
    }
}
