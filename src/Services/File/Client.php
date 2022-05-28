<?php

namespace RotHub\PHP\Services\File;

class Client extends \RotHub\PHP\Services\AbstractService
{
    /**
     * 所有文件.
     *
     * @param string $path 路径.
     * @param string $pattern 匹配模式.
     * @return array
     */
    public static function files(string $path, string $pattern = '*'): array
    {
        $dirs = glob($path . DIRECTORY_SEPARATOR . '*', GLOB_ONLYDIR);
        $files = glob($path . DIRECTORY_SEPARATOR . $pattern);
        $files = $files ? $files : [];
        $res = array_map('realpath', array_filter($files, 'is_file'));

        foreach ($dirs as $dir) {
            if (is_dir($dir)) {
                $sons = static::files($dir, $pattern);
                $res = array_merge($res, $sons);
            }
        }

        return $res;
    }
}
