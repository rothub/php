<?php

namespace RotHub\PHP\Services\WeChat;

use GuzzleHttp\HandlerStack;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use RotHub\PHP\Exceptions\Error;
use RotHub\PHP\Middlewares\LogMiddleware;
use RotHub\PHP\Middlewares\RetryMiddleware;
use RotHub\PHP\Providers\HttpProvider;
use RotHub\PHP\Providers\RequestProvider;
use RotHub\PHP\Providers\ResponseProvider;
use RotHub\PHP\Services\WeChat\WeChatAccessTokenMiddleware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Client extends \RotHub\PHP\Services\AbstractService
{
    /**
     * @inheritdoc
     */
    protected $default = [
        'appid' => '',
        'secret' => '',
        'token' => '',
        'aes_key' => '',
        'base_uri' => 'https://api.weixin.qq.com',
        'block_size' => 32,
        'log_path' => '',
        'verify' => true,
    ];

    /**
     * 响应.
     *
     * @param callable $handler 处理.
     * @param Request $request 请求.
     * @return Response
     */
    public function serve(
        callable $handler = null,
        Request $request = null
    ): Response {
        $request or $request = $this->app[RequestProvider::name()];

        if (!$this->validate($request)) {
            Error::fail('验证签名失败.');
        }

        $echostr = $request->get('echostr');
        $isEncrypt = $request->get('encrypt_type') ? true : false;
        if (is_null($echostr)) {
            $data = $this->xmlToArray($request->getContent());

            $isEncrypt and $data = $this->decrypt($data['Encrypt']);

            $res = $handler
                ? call_user_func($handler, $data)
                : null;

            $response = $this->response($res, $isEncrypt);
        } else {
            $response = $this->response($echostr, $isEncrypt);
        }

        return $response;
    }

    /**
     * 请求.
     *
     * @param string|UriInterface $uri 链接.
     * @param string $method GET|POST.
     * @param array $params 参数.
     * @param array $options 配置.
     * @return ResponseInterface
     */
    public function fetch(
        string|UriInterface $uri = '',
        string $method = 'GET',
        array $params = [],
        array $options = []
    ): ResponseInterface {
        $method = strtoupper($method);

        $stack = HandlerStack::create();
        $stack->push($this->accessToken()->build(), 'accessToken');
        $stack->push($this->retry()->build(), 'retry');

        if ($this->config['log_path']) {
            $stack->push($this->log()->build(), 'log');
        }

        $options = array_merge([
            'handler' => $stack,
            'verify' => $this->config['verify'],
            'base_uri' => $this->config['base_uri'],
        ], $options);
        $params and $method === 'GET'
            ? $options['query'] = $params
            : $options['json'] = $params;
        $response = $this->app[HttpProvider::name()]
            ->request($method, $uri, $options);
        $response->getBody()->rewind();
        return $response;
    }

    /**
     * 微信 ACCESS_TOKEN 中间件.
     *
     * @return WeChatAccessTokenMiddleware
     */
    protected function accessToken(): WeChatAccessTokenMiddleware
    {
        return WeChatAccessTokenMiddleware::fake($this->app, [
            'appid' => $this->config['appid'],
            'secret' => $this->config['secret'],
            'verify' => $this->config['verify'],
        ]);
    }

    /**
     * 微信重试中间件.
     *
     * @return RetryMiddleware
     */
    protected function retry(): RetryMiddleware
    {
        return RetryMiddleware::fake($this->app, [
            'handler' => function ($response) {
                $this->accessToken()->fetchAccessToken();

                return true;
            },
        ]);
    }

    /**
     * 微信日志中间件.
     *
     * @return LogMiddleware
     */
    protected function log(): LogMiddleware
    {
        return LogMiddleware::fake($this->app, [[
            'params' => [
                'path' => $this->config['log_path'],
                'level' => Logger::DEBUG,
            ]
        ]]);
    }

    /**
     * 验证.
     *
     * @param Request $request 请求.
     * @return bool
     */
    protected function validate(Request $request): bool
    {
        $token = $this->config['token'];
        $timestamp = $request->get('timestamp');
        $nonce = $request->get('nonce');
        $signature = $this->signature([$token, $timestamp, $nonce]);

        return $request->get('signature') === $signature;
    }

    /**
     * 签名.
     *
     * @param array $params 参数.
     * @return string
     */
    protected static function signature(array $params): string
    {
        sort($params, SORT_STRING);

        return sha1(implode($params));
    }

    /**
     * 数组转 XML.
     *
     * @param array $data 数据.
     * @return string
     */
    protected function arrayToXml(array $data): string
    {
        $xml = '<xml>';
        foreach ($data as $key => $value) {
            $xml .= '<' . $key . '>';

            if (is_array($value)) {
                $xml .= $this->arrayToXml($value);
            } else if (is_numeric($value)) {
                $xml .= $value;
            } else {
                $xml .= $this->cdata($value);
            }

            $xml .= '</' . $key . '>';
        }
        $xml .= '</xml>';

        return $xml;
    }

    /**
     * XML 转数组.
     *
     * @param string $xml XML.
     * @return array
     */
    protected function xmlToArray(string $xml): array
    {
        $xml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        $xml = json_decode(json_encode($xml), true);

        return $xml;
    }

    /**
     * 加密.
     *
     * @param string $xml XML.
     * @return string
     */
    protected function encrypt(string $xml): string
    {
        $text = substr(md5(uniqid(microtime(true), true)), -16);
        $text .= pack('N', strlen($xml)) . $xml;
        $text .= $this->config['appid'];

        $padding = bcsub($this->config['block_size'], (strlen($text) % $this->config['block_size']));
        $pattern = chr($padding);

        $xml = $text . str_repeat($pattern, $padding);
        $key = base64_decode($this->config['aes_key'] . '=', true);

        $encrypted = openssl_encrypt(
            $xml,
            'aes-' . (8 * strlen($key)) . '-cbc',
            $key,
            OPENSSL_NO_PADDING,
            substr($key, 0, 16)
        );
        $encrypted = base64_encode($encrypted);

        $nonce = substr($this->config['appid'], 0, 10);
        $timestamp = time();
        $signature = $this->signature([
            $this->config['token'],
            $timestamp,
            $nonce,
            $encrypted
        ]);

        $response = [
            'Encrypt' => $encrypted,
            'MsgSignature' => $signature,
            'TimeStamp' => $timestamp,
            'Nonce' => $nonce,
        ];

        return $this->arrayToXml($response);
    }

    /**
     * 解密.
     *
     * @param string $encrypt 加密消息体.
     * @return array
     */
    protected function decrypt(string $encrypt): array
    {
        $key = base64_decode($this->config['aes_key'] . '=', true);

        $decrypted = openssl_decrypt(
            base64_decode($encrypt, true),
            'aes-' . (8 * strlen($key)) . '-cbc',
            $key,
            OPENSSL_NO_PADDING,
            substr($key, 0, 16)
        );

        $pad = ord(substr($decrypted, -1));
        $pad < 1 || $pad > 32 and $pad = 0;

        $res = substr($decrypted, 0, bcsub(strlen($decrypted), $pad));

        $content = substr($res, 16, strlen($res));
        $contentLen = unpack('N', substr($content, 0, 4))[1];

        if (trim(substr($content, bcadd($contentLen, 4))) !== $this->config['appid']) {
            Error::fail('验证 APPID 失败.');
        }

        $decrypted = substr($content, 4, $contentLen);
        return $this->xmlToArray($decrypted);
    }

    /**
     * XML 纯文本.
     *
     * @param string $str 字符串.
     * @return string
     */
    protected function cdata(string $str): string
    {
        return sprintf('<![CDATA[%s]]>', $str);
    }

    /**
     * 响应.
     *
     * @param mixed $data 数据.
     * @param bool $isEncrypt 是否加密.
     * @return Response
     */
    protected function response(mixed $data, bool $isEncrypt): Response
    {
        $response = $this->app[ResponseProvider::name()];

        if ($data === null) {
            return $response::create('success');
        }

        if (is_array($data)) {
            $res = $isEncrypt
                ? $this->encrypt($this->arrayToXml($data))
                : $this->arrayToXml($data);
            return $response::create($res, 200, [
                'Content-Type' => 'application/xml',
            ]);
        }

        return $response::create($data);
    }
}
