<?php

use RotHub\PHP\Facades\WeChatPay;
use RotHub\PHP\Tests\TestCase;
use Symfony\Component\HttpFoundation\Request;

class WeChatPayTest extends TestCase
{
    /**
     * @test
     */
    public function testRequest()
    {
        $res = WeChatPay::fake($this->config())
            ->client()
            ->v3->merchant->fund->balance->{'{account_type}'}
            ->getAsync([
                'account_type' => 'BASIC',
                'verify' => false,
            ])
            ->then(static function ($response) {
                return $response->getBody()->getContents();
            })
            ->otherwise(static function ($e) {
                if ($e instanceof \GuzzleHttp\Exception\RequestException && $e->hasResponse()) {
                    echo $e->getResponse()->getBody(), PHP_EOL;
                } else {
                    echo $e->getMessage(), PHP_EOL;
                }
            })
            ->wait();

        $res = json_decode($res, true);
        $this->assertArrayHasKey('available_amount', $res);
        $this->assertArrayHasKey('pending_amount', $res);
    }

    /**
     * @test
     */
    public function testServe()
    {
        $header = json_decode(constant('WECHAT_PAY_CALLBACK_HEADER'), true);
        $content = constant('WECHAT_PAY_CALLBACK_BODY');

        $request = Request::create('', 'POST', [], [], [], $header, $content);
        $res = WeChatPay::fake($this->config())->serve($request);

        $this->assertArrayHasKey('out_trade_no', $res);
        $this->assertArrayHasKey('transaction_id', $res);
        $this->assertArrayHasKey('out_refund_no', $res);
    }

    protected function config()
    {
        $cert = $this->dir(constant('WECHAT_PAY_CERT_PATH'));

        return [
            'apiv3Key' => constant('WECHAT_PAY_APIV3KEY'),
            'mchid' => constant('WECHAT_PAY_MCHID'),
            'serial' => $cert . 'apiclient_cert.pem',
            'privateKey' => $cert . 'apiclient_key.pem',
            'certificate' => $cert . 'certificate.pem',
        ];
    }
}
