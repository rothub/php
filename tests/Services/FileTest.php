<?php

use RotHub\PHP\Facades\File;
use RotHub\PHP\Tests\TestCase;

class FileTest extends TestCase
{
    /**
     * @test
     */
    public function testFiles()
    {
        $res = File::files(__DIR__, '*?Test.php');

        $this->assertFileExists($res[0]);
    }
}
