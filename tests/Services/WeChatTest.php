<?php

use RotHub\PHP\Facades\WeChat;
use RotHub\PHP\Tests\TestCase;
use Symfony\Component\HttpFoundation\Request;

class WeChatTest extends TestCase
{
    /**
     * @test
     */
    public function testFetch()
    {
        $url = '/cgi-bin/get_api_domain_ip';

        $client = WeChat::fake($this->config());
        $response = $client->fetch($url);
        $res = $response->getBody()->getContents();

        $res = json_decode($res, true);
        $this->assertArrayHasKey('ip_list', $res);
    }

    /**
     * @test
     */
    public function testEvent()
    {
        $client = WeChat::fake($this->config());
        $response = $client->serve(function ($data) {
            $this->assertArrayHasKey('ToUserName', $data);
            $this->assertArrayHasKey('FromUserName', $data);

            return [
                'ToUserName' => $data['FromUserName'],
                'FromUserName' => $data['ToUserName'],
                'CreateTime' => time(),
                'MsgType' => 'text',
                'Content' => '收到了，哈哈',
            ];
        }, $this->request());
        // $response->send();
    }

    protected function request()
    {
        $uri = 'https://mp.domain.com/?signature=fdd6d548c21d78621b8d7e40e0df969b709c3b57&timestamp=1632900811&nonce=1058254976&openid=o-8Xj5j4GvspU-08eFjJmEI_-JVM';
        $content = '<xml><ToUserName><![CDATA[gh_4cce9bc46e69]]></ToUserName>
        <FromUserName><![CDATA[o-8Xj5j4GvspU-08eFjJmEI_-JVM]]></FromUserName>
        <CreateTime>1632900811</CreateTime>
        <MsgType><![CDATA[event]]></MsgType>
        <Event><![CDATA[subscribe]]></Event>
        <EventKey><![CDATA[]]></EventKey>
        </xml>';

        return Request::create($uri, 'POST', [], [], [], [], $content);
    }

    protected function config()
    {
        return [
            'appid' => constant('WECHAT_APPID'),
            'secret' => constant('WECHAT_SECRET'),
            'token' => constant('WECHAT_TOKEN'),
            'aes_key' => constant('WECHAT_AES_KEY'),
            'verify' => false,
        ];
    }
}
