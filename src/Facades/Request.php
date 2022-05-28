<?php

namespace RotHub\PHP\Facades;

/**
 * @method initialize(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
 * @method static createFromGlobals()
 * @method static create(string $uri, string $method = "GET", array $parameters = [], array $cookies = [], array $files = [], array $server = [], $content = null)
 * @method static setFactory(?callable $callable)
 * @method duplicate(?array $query = null, ?array $request = null, ?array $attributes = null, ?array $cookies = null, ?array $files = null, ?array $server = null)
 * @method __clone()
 * @method string __toString()
 * @method overrideGlobals()
 * @method static setTrustedProxies(array $proxies, int $trustedHeaderSet)
 * @method static getTrustedProxies()
 * @method static getTrustedHeaderSet()
 * @method static setTrustedHosts(array $hostPatterns)
 * @method static getTrustedHosts()
 * @method static normalizeQueryString(?string $qs)
 * @method static enableHttpMethodParameterOverride()
 * @method static getHttpMethodParameterOverride()
 * @method get(string $key, $default = null)
 * @method getSession()
 * @method hasPreviousSession()
 * @method hasSession()
 * @method setSession(Symfony\Component\HttpFoundation\Session\SessionInterface $session)
 * @method setSessionFactory(callable $factory)
 * @method getClientIps()
 * @method getClientIp()
 * @method getScriptName()
 * @method getPathInfo()
 * @method getBasePath()
 * @method getBaseUrl()
 * @method getScheme()
 * @method getPort()
 * @method getUser()
 * @method getPassword()
 * @method getUserInfo()
 * @method getHttpHost()
 * @method getRequestUri()
 * @method getSchemeAndHttpHost()
 * @method getUri()
 * @method getUriForPath(string $path)
 * @method getRelativeUriForPath(string $path)
 * @method getQueryString()
 * @method isSecure()
 * @method getHost()
 * @method setMethod(string $method)
 * @method getMethod()
 * @method getRealMethod()
 * @method getMimeType(string $format)
 * @method static getMimeTypes(string $format)
 * @method getFormat(?string $mimeType)
 * @method setFormat(?string $format, $mimeTypes)
 * @method getRequestFormat(?string $default = "html")
 * @method setRequestFormat(?string $format)
 * @method getContentType()
 * @method setDefaultLocale(string $locale)
 * @method getDefaultLocale()
 * @method setLocale(string $locale)
 * @method getLocale()
 * @method isMethod(string $method)
 * @method isMethodSafe()
 * @method isMethodIdempotent()
 * @method isMethodCacheable()
 * @method getProtocolVersion()
 * @method getContent(bool $asResource = false)
 * @method toArray()
 * @method getETags()
 * @method isNoCache()
 * @method ?string getPreferredFormat(?string $default = "html")
 * @method getPreferredLanguage(?array $locales = null)
 * @method getLanguages()
 * @method getCharsets()
 * @method getEncodings()
 * @method getAcceptableContentTypes()
 * @method isXmlHttpRequest()
 * @method bool preferSafeContent()
 * @method isFromTrustedProxy()
 *
 * @see \Symfony\Component\HttpFoundation\Request
 */
class Request extends \RotHub\PHP\Facades\AbstractFacade
{
    /**
     * @inheritdoc
     */
    protected $class = \RotHub\PHP\Providers\RequestProvider::class;
}
