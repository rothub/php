<?php

namespace RotHub\PHP\Facades;

/**
 * @method static create(?string $content = "", int $status = 200, array $headers = [])
 * @method string __toString()
 * @method __clone()
 * @method prepare(Symfony\Component\HttpFoundation\Request $request)
 * @method sendHeaders()
 * @method sendContent()
 * @method send()
 * @method setContent(?string $content)
 * @method getContent()
 * @method object setProtocolVersion(string $version)
 * @method string getProtocolVersion()
 * @method object setStatusCode(int $code, ?string $text = null)
 * @method int getStatusCode()
 * @method object setCharset(string $charset)
 * @method ?string getCharset()
 * @method bool isCacheable()
 * @method bool isFresh()
 * @method bool isValidateable()
 * @method object setPrivate()
 * @method object setPublic()
 * @method object setImmutable(bool $immutable = true)
 * @method bool isImmutable()
 * @method bool mustRevalidate()
 * @method ?DateTimeInterface getDate()
 * @method object setDate(DateTimeInterface $date)
 * @method int getAge()
 * @method expire()
 * @method ?DateTimeInterface getExpires()
 * @method object setExpires(?DateTimeInterface $date = null)
 * @method ?int getMaxAge()
 * @method object setMaxAge(int $value)
 * @method object setSharedMaxAge(int $value)
 * @method ?int getTtl()
 * @method object setTtl(int $seconds)
 * @method object setClientTtl(int $seconds)
 * @method ?DateTimeInterface getLastModified()
 * @method object setLastModified(?DateTimeInterface $date = null)
 * @method ?string getEtag()
 * @method object setEtag(?string $etag = null, bool $weak = false)
 * @method object setCache(array $options)
 * @method object setNotModified()
 * @method bool hasVary()
 * @method array getVary()
 * @method object setVary($headers, bool $replace = true)
 * @method bool isNotModified(Symfony\Component\HttpFoundation\Request $request)
 * @method bool isInvalid()
 * @method bool isInformational()
 * @method bool isSuccessful()
 * @method bool isRedirection()
 * @method bool isClientError()
 * @method bool isServerError()
 * @method bool isOk()
 * @method bool isForbidden()
 * @method bool isNotFound()
 * @method bool isRedirect(?string $location = null)
 * @method bool isEmpty()
 * @method static void closeOutputBuffers(int $targetLevel, bool $flush)
 * @method void setContentSafe(bool $safe = true)
 *
 * @see \Symfony\Component\HttpFoundation\Response
 */
class Response extends \RotHub\PHP\Facades\AbstractFacade
{
    /**
     * @inheritdoc
     */
    protected $class = \RotHub\PHP\Providers\ResponseProvider::class;
}
