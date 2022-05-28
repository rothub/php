<?php

namespace RotHub\PHP\Services\Sensitive;

class CharIterator implements \Iterator
{
    /**
     * @var string
     */
    protected $str;
    /**
     * @var string
     */
    protected $char;
    /**
     * @var int
     */
    protected $length = 0;
    /**
     * @var int
     */
    protected $index = 0;
    /**
     * @var int
     */
    protected $offset = 0;

    /**
     * @inheritdoc
     */
    public function __construct($str)
    {
        $this->str = $str;
    }

    /**
     * @inheritdoc
     */
    public function current(): mixed
    {
        return $this->char;
    }

    /**
     * @inheritdoc
     */
    public function key(): mixed
    {
        return $this->offset;
    }

    /**
     * @inheritdoc
     */
    public function next(): void
    {
        if ($this->offset >= $this->length) {
            $this->char = '';
        } else {
            $i = $this->offset;
            $c = $this->str[$i];
            $ord = ord($c);
            if ($ord < 128) {
                $this->char = $c;
            } elseif ($ord < 224) {
                $this->char = $c . $this->str[++$i];
            } elseif ($ord < 240) {
                $this->char = $c . $this->str[++$i] . $this->str[++$i];
            } else {
                $this->char = $c . $this->str[++$i] . $this->str[++$i] . $this->str[++$i];
            }

            $this->offset = bcadd($i, 1);
            $this->index = bcadd($this->index, 1);
        }
    }

    /**
     * @inheritdoc
     */
    public function rewind(): void
    {
        $this->offset = 0;
        $this->index = 0;
        $this->length = strlen($this->str);
        $this->char = '';
        $this->next();
    }

    /**
     * @inheritdoc
     */
    public function valid(): bool
    {
        return $this->char !== '';
    }
}
