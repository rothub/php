<?php

namespace RotHub\PHP\Services\Express;

use RotHub\PHP\Services\Express\KDNiao;

class Client extends \RotHub\PHP\Services\AbstractService
{
    /**
     * @inheritdoc
     */
    protected $default = [
        'kdniao' => [
            'ebusiness_id' => '',
            'app_key' => '',
        ],
    ];

    /**
     * 快递鸟请求.
     *
     * @param string $url 请求地址.
     * @param string $type 请求指令类型.
     * @param array $params 请求内容需进行 URL(utf-8) 编码.
     * 请求内容 JSON 格式，须和 DataType 一致.
     * @return array
     */
    public function kdniao(
        string $url,
        string $type,
        array $params
    ): array {
        return (new KDNiao($this->config['kdniao']))
            ->request($url, $type, $params);
    }
}
