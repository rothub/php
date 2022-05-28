<?php

use RotHub\PHP\Facades\Express;
use RotHub\PHP\Tests\TestCase;

class ExpressTest extends TestCase
{
    /**
     * @test
     */
    public function testKdniao()
    {
        $url = 'http://api.kdniao.com/Ebusiness/EbusinessOrderHandle.aspx';

        $res = Express::fake([
            'kdniao' => [
                'ebusiness_id' => constant('KDNIAO_EBUSINESS_ID'),
                'app_key' => constant('KDNIAO_APP_KEY'),
            ]
        ])->kdniao($url, '8001', [
            'ShipperCode' => 'YZPY',
            'LogisticCode' => '9899402217950',
            'CustomerName' => '',
        ]);

        $this->assertArrayHasKey('Success', $res);
    }
}
