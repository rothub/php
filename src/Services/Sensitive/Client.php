<?php

namespace RotHub\PHP\Services\Sensitive;

use RotHub\PHP\Services\Sensitive\CharIterator;

/**
 * 生成词库：
 * $filter = new SensitiveService();
 * $data = $filter->readfile('input_path'); // 读取文件数据.
 * $filter->filltrie($data); // 填充数据.
 * $filter->saveLexicon('output_path'); // 保存词库.
 *
 * 搜索：
 * $filter = new SensitiveService();
 * $filter->readLexicon('bin_path');
 * $res = $filter->search('some text here...');
 *
 * 替换：
 * $filter = new SensitiveService();
 * $filter->readLexicon('bin_path');
 * $replaced = $filter->replace('some text here...', '**');
 *
 * 高级替换：
 * $filter = new SensitiveService();
 * $filter->readLexicon('bin_path');
 * $replaced = $filter->replace('我要包二奶', function ($word, $value) {
 *          return "[$word -> $value]";
 *      }
 *  );
 */
class Client extends \RotHub\PHP\Services\AbstractService
{
    /**
     * char for padding value.
     */
    const CHAR_PAD = ' ';
    /**
     * stop chars.
     */
    const CHAR_STOP = ',.? ';

    /**
     * @var resource file handle.
     */
    protected $file;
    /**
     * @var array trie data.
     */
    protected $trie = [];
    /**
     * @var int fixed row length.
     */
    protected $rowLength = 0;
    /**
     * @var int fixed value length.
     */
    protected $valueLength = 0;
    /**
     * @var array first chars cache.
     */
    protected $start = [];

    /**
     * 读文件.
     *
     * @param string $filename 文件名.
     * @param string $separator 分隔符.
     * @return array
     */
    public function readfile(
        string $filename,
        string $separator = ','
    ): array {
        $fp = fopen($filename, 'r');

        while ($line = fgets($fp, 1024)) {
            $line = trim($line);
            empty($line) or $data[] = explode($separator, $line);
        }

        fclose($fp);

        return $data;
    }

    /**
     * 填充.
     *
     * @param array $words 词汇.
     * @return void
     */
    public function filltrie(array $words): void
    {
        foreach ($words as $item) {
            list($word, $value) = $item;

            $iterator = new CharIterator($word);
            $prefix = '';
            foreach ($iterator as $char) {
                $next = &$this->trie[$prefix]['next'];

                if (!isset($next) || !in_array($char, $next)) {
                    $next[] = $char;
                }

                $prefix .= $char;
            }

            if (strlen($value) > $this->valueLength) {
                $this->valueLength = strlen($value);
            }

            $this->trie[$word]['value'] = $value;
        }
    }

    /**
     * 保存词库.
     *
     * @param string $filename 文件名.
     * @return void
     */
    public function saveLexicon(string $filename): void
    {
        sort($this->trie['']['next'], SORT_STRING);
        $stack = [array_fill_keys($this->trie['']['next'], 0)];
        $prefix = [];

        $fp = fopen($filename, 'w');
        // header: count, valueLength, rowLength
        $line = pack(
            "nnn",
            count($stack[0]),
            $this->valueLength,
            bcadd($this->valueLength, 9)
        );
        fwrite($fp, $line);

        $offset = strlen($line);

        do {
            foreach ($stack[0] as $char => &$addr) {
                if ($addr > 0) {
                    continue;
                }

                $line = str_pad($char, 3, static::CHAR_PAD)
                    . pack("nN", 0, 0)
                    . str_repeat(
                        static::CHAR_PAD,
                        $this->valueLength
                    );
                fwrite($fp, $line);

                $addr = $offset;
                $offset = bcadd($offset, strlen($line));
            }

            $nextKeys = array_keys($stack[0]);
            $nextChar = $nextKeys[0];
            $next = $this->trie[implode('', $prefix) . $nextChar];
            $nextSize = count($next['next'] ?? []);
            $nextVal = $next['value'] ?? '';
            $line = pack("nN", $nextSize, $offset)
                . str_pad(
                    $nextVal,
                    $this->valueLength,
                    static::CHAR_PAD
                );
            fseek($fp, bcadd($stack[0][$nextChar], 3));
            fwrite($fp, $line);
            fseek($fp, $offset);

            if (isset($next['next'])) {
                $prefix[] = $nextChar;
                sort($next['next'], SORT_STRING);
                array_unshift(
                    $stack,
                    array_fill_keys($next['next'], 0)
                );
            } else {
                unset($stack[0][$nextChar]);
            }

            while (empty($stack[0]) && !empty($stack)) {
                array_shift($stack);

                if (empty($stack)) {
                    break;
                }

                $keys = array_keys($stack[0]);
                unset($stack[0][$keys[0]]);
                array_pop($prefix);
            }
        } while (!empty($stack));

        fclose($fp);
    }

    /**
     * 读取词库.
     *
     * @param string $filename 文件名.
     * @return void
     */
    public function readLexicon(string $filename): void
    {
        $this->file = fopen($filename, 'r');
        $unpack = unpack("n3", fread($this->file, 6));
        $count = $unpack[1];
        $this->valueLength = $unpack[2];
        $this->rowLength = $unpack[3];

        foreach ($this->readLine(6, $count) as $line) {
            list($fChar, $fCount, $fOffset, $fValue) = $line;
            $this->start[$fChar] = [$fCount, $fOffset, $fValue];
        }
    }

    /**
     * 搜索:
     * [
     *   'word1' => ['value' => 'value1', 'count' => 'count1'],
     *   ...
     * ]
     *
     * @param string $str 字符串.
     * @return array
     */
    public function search(string $str): array
    {
        $ret = [];
        $iterator = new CharIterator($str);
        $stops = static::CHAR_STOP;

        $buff = [];
        foreach ($iterator as $char) {
            if (strpos($stops, $char) !== false) {
                $buff = [];
                continue;
            }

            foreach ($buff as $prefix => $next) {
                $newPrefix = $prefix . $char;
                list(
                    $count,
                    $offset,
                    $value
                ) = $this->findWord($char, $next[0], $next[1]);

                if (!empty($value)) {
                    if (isset($ret[$newPrefix])) {
                        $ret[$newPrefix]['count']++;
                    } else {
                        $ret[$newPrefix] = [
                            'count' => 1,
                            'value' => $value
                        ];
                    }
                }

                if ($count > 0) {
                    $buff[$newPrefix] = [$count, $offset];
                }

                unset($buff[$prefix]);
            }

            if (isset($this->start[$char])) {
                list(
                    $count,
                    $offset,
                    $value
                ) = $this->start[$char];

                if (!empty($value)) {
                    if (isset($ret[$char])) {
                        $ret[$char]['count']++;
                    } else {
                        $ret[$char] = [
                            'count' => 1,
                            'value' => $value
                        ];
                    }
                }

                if ($count > 0 && !isset($buff[$char])) {
                    $buff[$char] = [$count, $offset];
                }
            }
        }

        return $ret;
    }

    /**
     * 替换.
     *
     * @param string $str 字符串.
     * @param callable|string $to 替换处理.
     * @return string
     */
    public function replace(string $str, callable|string $to): string
    {
        $ret = '';
        $iterator = new CharIterator($str);
        $stops = static::CHAR_STOP;

        $buff = '';
        $size = 0;
        $offset = 0;
        $buffValue = [];
        foreach ($iterator as $char) {
            if (strpos($stops, $char) !== false) {
                if (empty($buffValue)) {
                    $ret .= $buff . $char;
                } else {
                    $ret .= $this->replaceTo(
                        $buffValue[0],
                        $buffValue[1],
                        $to
                    );
                    $ret .= substr($buff, strlen($buffValue[0]));
                    $ret .= $char;
                }

                $buff = '';
                $buffValue = [];

                continue;
            }

            if ($buff !== '') {
                list(
                    $fCount,
                    $fOffset,
                    $fValue
                ) = $this->findWord($char, $size, $offset);

                if ($fValue === null) {
                    if (empty($buffValue)) {
                        $ret .= $buff;
                    } else {
                        $ret .= $this->replaceTo(
                            $buffValue[0],
                            $buffValue[1],
                            $to
                        );
                        $ret .= substr($buff, strlen($buffValue[0]));
                    }

                    $buff = '';
                    $buffValue = [];
                } else {
                    if ($fCount > 0) {
                        $buff .= $char;
                        $size = $fCount;
                        $offset = $fOffset;

                        if (!empty($fValue)) {
                            $buffValue = [$buff, $fValue];
                        }
                    } else {
                        $ret .= $this->replaceTo($buff . $char, $fValue, $to);
                        $buff = '';
                        $buffValue = [];
                    }

                    continue;
                }
            }

            if (isset($this->start[$char])) {
                list(
                    $fCount,
                    $fOffset,
                    $fValue
                ) = $this->start[$char];

                if ($fCount > 0) {
                    $buff = $char;
                    $size = $fCount;
                    $offset = $fOffset;

                    if (!empty($fValue))
                        $buffValue = [$buff, $fValue];
                } else {
                    $ret .= $this->replaceTo($char, $fValue, $to);
                }
            } else {
                $ret .= $char;
            }
        }

        if ($buff !== '') {
            if (empty($buffValue)) {
                $ret .= $buff;
            } else {
                $ret .= $this->replaceTo(
                    $buffValue[0],
                    $buffValue[1],
                    $to
                ) . substr($buff, strlen($buffValue[0]));
            }
        }

        return $ret;
    }

    /**
     * 替换成.
     *
     * @param string $word
     * @param string $value
     * @param callable|string $to
     * @return string
     */
    protected function replaceTo(
        string $word,
        string $value,
        callable|string $to
    ): string {
        return is_callable($to)
            ? call_user_func($to, $word, $value)
            : $to;
    }

    /**
     * from $offset, find $char, up to $count record.
     *
     * @param string $char
     * @param int $count
     * @param int $offset
     * @return array($count, $offset, $value)
     */
    protected function findWord(
        string $char,
        int $count,
        int $offset
    ): array {
        fseek($this->file, $offset);
        $len = $this->rowLength;
        $data = fread($this->file, bcmul($count, $len));

        for ($i = 0; $i < $count; $i++) {
            $row = substr($data, bcmul($i, $len), $len);
            $un = unpack("c3char/ncount/Noffset/c*value", $row);
            $fChar = rtrim(chr($un['char1'])
                . chr($un['char2'])
                . chr($un['char3']));

            if ($fChar !== $char) {
                continue;
            }

            $fCount = $un['count'];
            $fOffset = $un['offset'];

            $fValue = '';
            for ($j = 1; $j <= bcsub($this->rowLength, 9); $j++) {
                $v = $un['value' . $j];

                if ($v == 32) {
                    break;
                }

                $fValue .= chr($v);
            }

            return [$fCount, $fOffset, $fValue];
        }

        return [0, 0, null];
    }

    /**
     * 读取一行.
     *
     * @param int $offset
     * @param int $size
     * @return array
     */
    protected function readLine(int $offset, int $size): array
    {
        $ret = [];
        fseek($this->file, $offset);
        $data = fread($this->file, bcmul($size, $this->rowLength));

        for ($i = 0; $i < $size; $i++) {
            $row = substr(
                $data,
                bcmul($i, $this->rowLength),
                $this->rowLength
            );
            $un = unpack("c3char/ncount/Noffset/c*value", $row);
            $fChar = rtrim(chr($un['char1'])
                . chr($un['char2'])
                . chr($un['char3']));
            $fCount = $un['count'];
            $fOffset = $un['offset'];

            $fValue = '';
            for ($j = 1; $j <= bcsub($this->rowLength, 9); $j++) {
                $v = $un['value' . $j];

                if ($v == 32) {
                    break;
                }

                $fValue .= chr($v);
            }

            $ret[] = [$fChar, $fCount, $fOffset, $fValue];
        }

        return $ret;
    }

    /**
     * @inheritdoc
     */
    public function __destruct()
    {
        unset($this->start);

        $this->file and fclose($this->file);
    }
}
