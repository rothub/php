<?php

namespace RotHub\PHP\Middlewares;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RotHub\PHP\Middlewares\AbstractMiddleware;

class RetryMiddleware extends AbstractMiddleware
{
    /**
     * @inheritdoc
     */
    protected $default = [
        'max' => 1,
        'delay' => 500,
    ];

    /**
     * @inheritdoc
     */
    public function build(): callable
    {
        return Middleware::retry(
            function (
                $retries,
                RequestInterface $request,
                ResponseInterface $response = null,
                RequestException $exception = null
            ) {
                return $this->decider(
                    $retries,
                    $request,
                    $response,
                    $exception
                );
            },
            function () {
                return $this->delay();
            }
        );
    }

    /**
     * 是否重试.
     *
     * @param int $retries 重试次数.
     * @param RequestInterface $request 请求.
     * @param ResponseInterface $response 响应.
     * @param RequestException $exception 异常.
     * @return bool
     */
    protected function decider(
        int $retries,
        RequestInterface $request,
        ResponseInterface $response = null,
        RequestException $exception = null
    ): bool {
        // 请求超过最大重试次数，不再重试.
        if ($retries >= $this->config['max']) {
            return false;
        }

        // 请求失败，继续重试.
        if ($exception instanceof ConnectException) {
            return true;
        }

        // 请求有响应，根据业务处理.
        if ($response && isset($this->config['handler'])) {
            return call_user_func(
                $this->config['handler'],
                $retries,
                $request,
                $response,
                $exception
            );
        }

        return false;
    }

    /**
     * 延迟毫秒数.
     *
     * @return int
     */
    protected function delay(): int
    {
        return $this->config['delay'];
    }
}
