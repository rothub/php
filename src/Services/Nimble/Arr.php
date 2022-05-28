<?php

namespace RotHub\PHP\Services\Nimble;

use RotHub\PHP\Services\Nimble\Base;

class Arr extends Base
{
    /**
     * 分组.
     * 
     * @param string $key 键名.
     * @return array
     */
    public function group(string $key): array
    {
        $res = [];
        $rows = $this->raw;

        foreach ($rows as $row) {
            if (!array_key_exists($key, $row)) {
                $row[$key] = [];
            }

            $res[$row[$key]][] = $row;
        }

        return $res;
    }

    /**
     * 根据二维数组其中一个键值排序（后2个参数参照 array_multisort）.
     * 
     * @param array $array 已排序的键值数组.
     * @param string $key 键名.
     * @param mixed $order 排序顺序.
     * @param mixed $flags 排序类型.
     * @return array
     */
    public function sortByArray(
        array $array,
        string $key,
        mixed $order = SORT_ASC,
        mixed $flags = SORT_REGULAR
    ): array {
        $sort = [];
        $rows = $this->raw;

        foreach ($rows as &$row) {
            $sort[] = array_search($row[$key], $array);
        }

        array_multisort($sort, $order, $flags, $rows);

        return $rows;
    }
}
