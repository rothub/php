<?php

namespace RotHub\PHP\Services\Tree;

class Client extends \RotHub\PHP\Services\AbstractService
{
    /**
     * @var array 数据.
     */
    protected $data = [];
    /**
     * @var string 顶级值.
     */
    protected $topValue = '';
    /**
     * @var string ID 字段.
     */
    protected $idField = 'id';
    /**
     * @var string 父级字段.
     */
    protected $parentField = 'parent_id';
    /**
     * @var string 子级字段.
     */
    protected $childrenField = 'children';

    /**
     * 设置数据.
     *
     * @param array $data 数据.
     * @return static
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * 设置顶级值.
     *
     * @param string $value 值.
     * @return static
     */
    public function setTopValue(string $value): static
    {
        $this->topValue = $value;

        return $this;
    }

    /**
     * 设置 ID 字段.
     *
     * @param string $value 值.
     * @return static
     */
    public function setIdField(string $value): static
    {
        $this->idField = $value;

        return $this;
    }

    /**
     * 设置父级字段.
     *
     * @param string $value 值.
     * @return static
     */
    public function setParentField(string $value): static
    {
        $this->parentField = $value;

        return $this;
    }

    /**
     * 设置子级字段.
     *
     * @param string $value 值.
     * @return static
     */
    public function setChildrenField(string $value): static
    {
        $this->childrenField = $value;

        return $this;
    }

    /**
     * 生成.
     *
     * @return array
     */
    public function build(): array
    {
        $res = [];

        foreach ($this->data as $item) {
            $res[$item[$this->idField]] = $item;
        }

        foreach ($res as $item) {
            $res[$item[$this->parentField]][$this->childrenField][] = &$res[$item[$this->idField]];
        }

        return $res[$this->topValue][$this->childrenField] ?? [];
    }
}
