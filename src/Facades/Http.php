<?php

namespace RotHub\PHP\Facades;

/**
 * @method __call($method, $args)
 * @method GuzzleHttp\Promise\PromiseInterface sendAsync(Psr\Http\Message\RequestInterface $request, array $options = [])
 * @method Psr\Http\Message\ResponseInterface send(Psr\Http\Message\RequestInterface $request, array $options = [])
 * @method Psr\Http\Message\ResponseInterface sendRequest(Psr\Http\Message\RequestInterface $request)
 * @method GuzzleHttp\Promise\PromiseInterface requestAsync(string $method, $uri = "", array $options = [])
 * @method Psr\Http\Message\ResponseInterface request(string $method, $uri = "", array $options = [])
 * @method getConfig(?string $option = null)
 * @method Psr\Http\Message\ResponseInterface get($uri, array $options = [])
 * @method Psr\Http\Message\ResponseInterface head($uri, array $options = [])
 * @method Psr\Http\Message\ResponseInterface put($uri, array $options = [])
 * @method Psr\Http\Message\ResponseInterface post($uri, array $options = [])
 * @method Psr\Http\Message\ResponseInterface patch($uri, array $options = [])
 * @method Psr\Http\Message\ResponseInterface delete($uri, array $options = [])
 * @method GuzzleHttp\Promise\PromiseInterface getAsync($uri, array $options = [])
 * @method GuzzleHttp\Promise\PromiseInterface headAsync($uri, array $options = [])
 * @method GuzzleHttp\Promise\PromiseInterface putAsync($uri, array $options = [])
 * @method GuzzleHttp\Promise\PromiseInterface postAsync($uri, array $options = [])
 * @method GuzzleHttp\Promise\PromiseInterface patchAsync($uri, array $options = [])
 * @method GuzzleHttp\Promise\PromiseInterface deleteAsync($uri, array $options = [])
 *
 * @see \GuzzleHttp\Client
 */
class Http extends \RotHub\PHP\Facades\AbstractFacade
{
    /**
     * @inheritdoc
     */
    protected $class = \RotHub\PHP\Providers\HttpProvider::class;
}
