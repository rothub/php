<?php

namespace RotHub\PHP\Services\Sequence;

use RotHub\PHP\Exceptions\Error;
use RotHub\PHP\Services\Sequence\SequenceInterface;

class Client extends \RotHub\PHP\Services\AbstractService
{
    /**
     * @inheritdoc
     */
    protected $default = [
        'min' => 0,
        'max' => 999999999999,
        'step' => 1,
        'length' => 12,
        'prefix' => '',
        'suffix' => '',
        'secret' => 127466127478,
    ];

    /**
     * 生成 ID.
     *
     * @return string
     */
    public function id(): string
    {
        $sequence = $this->call();
        $sequence = str_pad($sequence, $this->config['length'], '0', STR_PAD_LEFT);

        return join('', [
            $this->config['prefix'],
            $sequence,
            $this->config['suffix'],
        ]);
    }

    /**
     * 加密生成 ID.
     *
     * @return string
     */
    public function encode(): string
    {
        $sequence = $this->call();
        $sequence = bcadd(bcadd($sequence, time()), $this->config['secret']);
        $sequence = str_pad($sequence, $this->config['length'], '0', STR_PAD_LEFT);

        return join('', [
            $this->config['prefix'],
            $sequence,
            $this->config['suffix'],
        ]);
    }

    /**
     * 序列号提供者.
     *
     * @return SequenceInterface|callable
     */
    public function sequence(): SequenceInterface|callable
    {
        if (is_null($this->sequence)) {
            Error::fail('Invalid sequence.');
        }

        return $this->sequence;
    }

    /**
     * 设置序列号提供者.
     *
     * @param SequenceInterface|callable $sequence 序列号提供者.
     * @return static
     */
    public function setSequence(SequenceInterface|callable $sequence): static
    {
        if (
            is_null($sequence)
            && !is_callable($sequence)
            && !($sequence instanceof SequenceInterface)
        ) {
            Error::fail('Invalid sequence.');
        }

        $this->sequence = $sequence;

        return $this;
    }

    /**
     * 调用序列号.
     * 
     * @return int
     */
    protected function call(): int
    {
        $sequence = $this->sequence();

        if (is_callable($sequence)) {
            return $sequence();
        }

        $no = $sequence->next(
            $this->config['min'],
            $this->config['step']
        );

        if ($no > $this->config['max']) {
            Error::fail('The sequence can\'t be greater than max sequence.');
        }

        return $no;
    }
}
