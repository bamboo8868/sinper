<?php

namespace BambooX\Sniper\Buffer;

class Buffer
{
    public array $buf = [];

    public static function from(string $str): Buffer
    {
        $buf = new self();
        $buf->write($str);
        return $buf;
    }

    public function write($str)
    {
        if ($str instanceof Buffer) {
            foreach ($str->buf as $hex) {
                $this->buf = $hex;
            }
        } else if (is_string($str)) {
            $len = strlen($str);
            for ($i = 0; $i < $len; $i++) {
                $hex = dechex(ord($str[$i]));
                if (strlen($hex) < 2) {
                    $hex = str_pad($hex, 2, 0, STR_PAD_LEFT);
                }
                $this->buf[] = $hex;
            }
        }
    }


    public function writeInt(int $num)
    {
        $this->writeInt32($num);
    }

    public function writeInt32(int $num)
    {
        $str = dechex($num);
        if (strlen($str) < 8) {
            $str = str_pad($str, 8, 0, STR_PAD_LEFT);
        }
        foreach (str_split($str, 2) as $val) {
            $this->buf[] = $val;
        }
    }

    public function writeInt64(int $num)
    {
        $str = dechex($num);
        if ($str < 16) {
            $str = str_pad($str, 16, 0, STR_PAD_LEFT);
        }
        foreach (str_split($str, 2) as $val) {
            $this->buf[] = $val;
        }
    }

    public function toString(): bool|string
    {
        $str = implode('', $this->buf);
        return hex2bin($str);
    }
}