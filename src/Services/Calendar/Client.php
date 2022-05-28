<?php

namespace RotHub\PHP\Services\Calendar;

class Client extends \RotHub\PHP\Services\AbstractService
{
    const URL_GOV = 'http://sousuo.gov.cn/s.htm?t=bulletin&advance=false&n=&codeYear=&codeCode=&searchfield=title&sort=&q=%E8%8A%82%E5%81%87%E6%97%A5';

    /**
     * 法定节假日.
     *
     * @return array
     */
    public static function statute(int $year): array
    {
        $bulletin = static::bulletin($year);
        $res['holidays'] = static::holidays($bulletin, $year);
        $res['weekdays'] = static::weekdays($bulletin, $year);

        return $res;
    }

    protected static function bulletin(int $year): string|false
    {
        $year--;
        $html = static::fetch(static::URL_GOV);

        $regex = "/\"(http|https):\/\/www.gov.cn\/gongbao\/content\/$year(.*?)\"/iux";
        preg_match($regex, $html, $matches);

        return static::fetch(substr($matches[0] ?? '', 1, -1));
    }

    protected static function holidays(string $html, int $year): array
    {
        $res = [];

        $regex = "/\d+月\d+日至(.*?)日/";
        preg_match_all($regex, $html, $matches);
        foreach ($matches[0] as $matche) {
            $around = explode('至', $matche);
            if (strpos('月', $around[1]) === false) {
                preg_match("/\d+月/", $around[0], $months);
                $around[1] = $months[0] . $around[1];
            }

            $interval = new \DateInterval('P1D');
            $period = new \DatePeriod(
                static::datetime($year . '年' . $around[0]),
                $interval,
                static::datetime($year . '年' . $around[1])->add($interval)
            );
            foreach ($period as $date) {
                $res[] = $date->format('Y-m-d');
            }
        }

        return $res;
    }

    protected static function weekdays(string $html, int $year): array
    {
        $res = [];

        $regex = "/\d+月\d+日(.{0,3})星期/";
        preg_match_all($regex, $html, $matches);
        foreach ($matches[0] as $matche) {
            preg_match("/\d+月\d+日/", $matche, $days);

            $res[] = static::datetime($year . '年' . $days[0])->format('Y-m-d');
        }

        return $res;
    }

    protected static function fetch(string $url): string|false
    {
        return file_get_contents($url);
    }

    protected static function datetime(string $str): \DateTime
    {
        return new \DateTime(static::format($str));
    }

    protected static function format(string $str): string
    {
        return str_replace(['年', '月', '日'], ['-', '-'], $str);
    }
}
