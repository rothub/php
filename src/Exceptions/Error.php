<?php

namespace RotHub\PHP\Exceptions;

use Symfony\Component\HttpFoundation\Response as Http;

class Error extends \RuntimeException
{
    /**
     * 异常信息: 默认错误信息.
     */
    const FAIL = '操作失败.';

    /**
     * 编码: 请求失败.
     */
    const CODE_BAD = Http::HTTP_BAD_REQUEST;

    /**
     * 失败.
     * 
     * @param string $message 错误信息.
     * @param int $code 错误编码.
     * @param Throwable|null $previous 异常链中的前一个异常.
     * @return void
     */
    public static function fail(
        string $message = self::FAIL,
        int $code = self::CODE_BAD,
        ?\Throwable $previous = null
    ): void {
        throw new static($message, $code, $previous);
    }
}
