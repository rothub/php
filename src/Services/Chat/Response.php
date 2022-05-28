<?php

namespace RotHub\PHP\Services\Chat;

class Response
{
    /**
     * @var string 消息通知.
     */
    const TYPE_NOTICE = 'notice';
    /**
     * @var string 响应通知.
     */
    const TYPE_RESPONSE = 'response';
    /**
     * @var string 聊天通知.
     */
    const TYPE_CHAT = 'chat';

    /**
     * @var string 消息类型.
     */
    protected $type;
    /**
     * @var mixed 消息内容.
     */
    protected $data;

    /**
     * 设置消息类型.
     *
     * @param string $type 类型.
     * @return static
     */
    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * 设置消息内容.
     *
     * @param mixed $data 内容.
     * @return static
     */
    public function setData(mixed $data): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * 格式化消息.
     *
     * @return string
     */
    public function json(): string
    {
        return json_encode([
            'type' => $this->type,
            'data' => $this->data,
        ]);
    }
}
