<?php

namespace RotHub\PHP\Facades;

/**
 * @method WeChatPay\BuilderChainable client()
 * @method string mchSerial()
 * @method mixed mchKey()
 * @method string certSerial()
 * @method mixed certKey()
 * @method string sign(string $prepayid)
 * @method string encrypt(string $str)
 * @method string decrypt(string $ciphertext, string $nonce, string $aad)
 * @method array serve(?Symfony\Component\HttpFoundation\Request $request = null)
 *
 * @see \RotHub\PHP\Services\WeChatPay\Client
 */
class WeChatPay extends \RotHub\PHP\Facades\AbstractFacade
{
    /**
     * @inheritdoc
     */
    protected $class = \RotHub\PHP\Providers\WeChatPayProvider::class;
}
