<?php

namespace RotHub\PHP\Services\WeChat;

use RotHub\PHP\Exceptions\Error;
use RotHub\PHP\Middlewares\AbstractMiddleware;
use RotHub\PHP\Providers\CacheProvider;
use RotHub\PHP\Providers\HttpProvider;

class WeChatAccessTokenMiddleware extends AbstractMiddleware
{
    /**
     * @inheritdoc
     */
    protected $default = [
        'uri' => '/cgi-bin/token',
        'base_uri' => 'https://api.weixin.qq.com',
        'appid' => '',
        'secret' => '',
        'key' => 'access_token',
        'expires_in' => 7200,
        'cache_prefix' => 'rothub.wechat.access_token.',
    ];

    /**
     * ACCESS_TOKEN.
     *
     * @return string
     */
    public function accessToken(): string
    {
        $cache = $this->app[CacheProvider::name()];
        $cacheKey = $this->cacheKey();

        if ($cache->has($cacheKey)) {
            return $cache->get($cacheKey)[$this->config['key']];
        }

        return $this->fetchAccessToken();
    }

    /**
     * 请求 ACCESS_TOKEN.
     *
     * @return string
     */
    public function fetchAccessToken(): string
    {
        $http = $this->app[HttpProvider::name()];
        $url = $this->config['uri'];
        $options = [
            'verify' => $this->config['verify'],
            'base_uri' => $this->config['base_uri'],
            'query' => [
                'grant_type' => 'client_credential',
                'appid' => $this->config['appid'],
                'secret' => $this->config['secret'],
            ],
        ];

        $response = $http->request('GET', $url, $options);
        $response->getBody()->rewind();
        $res = $response->getBody()->getContents();
        $res = json_decode($res, true);

        if (empty($res[$this->config['key']])) {
            Error::fail('请求 access_token 失败: ' . json_encode($res) . '.');
        }

        $this->app[CacheProvider::name()]
            ->set($this->cacheKey(), [
                'access_token' => $res[$this->config['key']],
                'expires_in' => $this->config['expires_in'],
            ], (int)bcsub($this->config['expires_in'], 500));

        return $res[$this->config['key']];
    }

    /**
     * @inheritdoc
     */
    protected function request(&$request, &$options): void
    {
        parse_str($request->getUri()->getQuery(), $query);

        $accessToken = $this->accessToken();
        $query[$this->config['key']] = $accessToken;
        $query = http_build_query($query);

        $uri = $request->getUri()->withQuery($query);
        $request = $request->withUri($uri);
    }

    /**
     * 缓存键名.
     *
     * @return string
     */
    protected function cacheKey(): string
    {
        return $this->config['cache_prefix']
            . md5(json_encode([
                $this->config['appid'],
                $this->config['secret'],
            ]));
    }
}
