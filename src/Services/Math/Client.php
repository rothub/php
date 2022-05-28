<?php

namespace RotHub\PHP\Services\Math;

class Client extends \RotHub\PHP\Services\AbstractService
{
    /**
     * 加法.
     *
     * @param string $num1 数1.
     * @param string $num2 数2.
     * @param string $scale 小数位数.
     * @return string
     */
    public static function add(string $num1, string $num2, int $scale = 2): string
    {
        return bcadd($num1, $num2, $scale);
    }

    /**
     * 减法.
     *
     * @param string $num1 数1.
     * @param string $num2 数2.
     * @param string $scale 小数位数.
     * @return string
     */
    public static function sub(string $num1, string $num2, int $scale = 2): string
    {
        return bcsub($num1, $num2, $scale);
    }

    /**
     * 乘法.
     *
     * @param string $num1 数1.
     * @param string $num2 数2.
     * @param string $scale 小数位数.
     * @return string
     */
    public static function mul(string $num1, string $num2, int $scale = 2): string
    {
        return bcmul($num1, $num2, $scale);
    }

    /**
     * 除法.
     *
     * @param string $num1 数1.
     * @param string $num2 数2.
     * @param string $scale 小数位数.
     * @return string
     */
    public static function div(string $num1, string $num2, int $scale = 2): string
    {
        $num2 == 0 and $num2 = 1;

        return bcdiv($num1, $num2, $scale);
    }

    /**
     * 百分比.
     *
     * @param string $num1 数1.
     * @param string $num2 数2.
     * @param string $scale 小数位数.
     * @return string
     */
    public static function percent(string $num1, string $num2, int $scale = 2): string
    {
        $num2 == 0 and $num2 = 1;

        $value = bcdiv($num1, $num2, $scale + 2);

        return bcmul($value, 100, $scale) . '%';
    }
}
