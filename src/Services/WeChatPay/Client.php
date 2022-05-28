<?php

namespace RotHub\PHP\Services\WeChatPay;

use RotHub\PHP\Providers\RequestProvider;
use Symfony\Component\HttpFoundation\Request;
use WeChatPay\Builder;
use WeChatPay\BuilderChainable;
use WeChatPay\Crypto\AesGcm;
use WeChatPay\Crypto\Rsa;
use WeChatPay\Formatter;
use WeChatPay\Util\PemUtil;

class Client extends \RotHub\PHP\Services\AbstractService
{
    /**
     * @inheritdoc
     */
    protected $default = [
        'appId' => '',
        'mchid' => '',
        'serial' => '',
        'privateKey' => '',
        'certificate' => '',
        'apiv3Key' => '',
    ];

    /**
     * HTTP 客户端.
     *
     * @return BuilderChainable
     */
    public function client(): BuilderChainable
    {
        return Builder::factory([
            'mchid' => $this->config['mchid'],
            'serial' => $this->mchSerial(),
            'privateKey' => $this->mchKey(),
            'certs' => [$this->certSerial() => $this->certKey()],
        ]);
    }

    /**
     * 商户证书序列号.
     *
     * @return string
     */
    public function mchSerial(): string
    {
        $serial = 'file://' . $this->config['serial'];

        return PemUtil::parseCertificateSerialNo($serial);
    }

    /**
     * 商户私钥.
     *
     * @return mixed
     */
    public function mchKey(): mixed
    {
        $key = 'file://' . $this->config['privateKey'];

        return Rsa::from($key, Rsa::KEY_TYPE_PRIVATE);
    }

    /**
     * 平台证书序列号.
     *
     * @return string
     */
    public function certSerial(): string
    {
        $cert = 'file://' . $this->config['certificate'];

        return PemUtil::parseCertificateSerialNo($cert);
    }

    /**
     * 平台证书公钥.
     *
     * @return mixed
     */
    public function certKey(): mixed
    {
        $cert = 'file://' . $this->config['certificate'];

        return Rsa::from($cert, Rsa::KEY_TYPE_PUBLIC);
    }

    /**
     * APIv3小程序/JSAPI调起支付数据签名.
     *
     * @param string $prepayid 预支付交易会话标识.
     * @return string
     */
    public function sign(string $prepayid): string
    {
        $mchKey = $this->mchKey();

        $params = [
            'appId' => $this->config['appId'],
            'timeStamp' => (string)Formatter::timestamp(),
            'nonceStr' => Formatter::nonce(),
            'package' => 'prepay_id=' . $prepayid,
        ];

        $params += ['paySign' => Rsa::sign(
            Formatter::joinedByLineFeed(...array_values($params)),
            $mchKey
        ), 'signType' => 'RSA'];

        return json_encode($params);
    }

    /**
     * 加密.
     *
     * @param string $str 字符串.
     * @return string
     */
    public function encrypt(string $str): string
    {
        return Rsa::encrypt($str, $this->certKey());
    }

    /**
     * 解密.
     *
     * @param string $ciphertext
     * @param string $nonce
     * @param string $aad
     * @return string
     */
    public function decrypt(
        string $ciphertext,
        string $nonce,
        string $aad
    ): string {
        $apiv3Key = $this->config['apiv3Key'];

        return AesGcm::decrypt($ciphertext, $apiv3Key, $nonce, $aad);
    }

    /**
     * 回调通知.
     *
     * @param Request $request 请求.
     * @return array
     */
    public function serve(Request $request = null): array
    {
        $request or $request = $this->app[RequestProvider::name()];

        $signature = $request->headers->get('Wechatpay-Signature');
        $nonce = $request->headers->get('Wechatpay-Nonce');
        $timestamp = $request->headers->get('Wechatpay-Timestamp');
        $serial = $request->headers->get('Wechatpay-Serial');
        $requestid = $request->headers->get('Request-ID');

        $json = $request->getContent();

        $certs = [$this->certSerial() => $this->certKey()];

        // 检查通知时间偏移量，允许5分钟之内的偏移.
        $timeOffsetStatus = 300 >= abs(Formatter::timestamp() - (int)$timestamp);
        $verifiedStatus = Rsa::verify(
            // 构造验签名串.
            Formatter::response($timestamp, $nonce, $json),
            $signature,
            $certs[$serial] ?? ''
        );
        $timeOffsetStatus = true;
        if ($timeOffsetStatus && $verifiedStatus) {
            $data = (array)json_decode($json, true);
            // 使用PHP7的数据解构语法，从Array中解构并赋值变量
            ['resource' => [
                'ciphertext' => $ciphertext,
                'nonce' => $nonce,
                'associated_data' => $aad
            ]] = $data;

            $decrypt = $this->decrypt($ciphertext, $nonce, $aad);
            return (array)json_decode($decrypt, true);
        }

        return [];
    }
}
