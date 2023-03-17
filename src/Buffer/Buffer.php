<?php

namespace BambooX\Sniper\Buffer;

class Buffer
{
    public array $buf = [];

    public int $readIndex = 0;

    public int $writeIndex = 0;

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
                $this->buf[] = $hex;
                $this->writeIndex++;
            }
        } else if (is_string($str)) {
            $len = strlen($str);
            for ($i = 0; $i < $len; $i++) {
                $hex = dechex(ord($str[$i]));
                if (strlen($hex) < 2) {
                    $hex = str_pad($hex, 2, 0, STR_PAD_LEFT);
                }
                $this->buf[] = $hex;
                $this->writeIndex++;
            }
        }
    }


    public function writeInt(int $num)
    {
        $this->writeInt32($num);
    }

    public function writeInt16(int $num)
    {
        $str = dechex($num);
        if (strlen($str) < 4) {
            $str = str_pad($str, 4, 0, STR_PAD_LEFT);
        }
        foreach (str_split($str, 2) as $val) {
            $this->buf[$this->writeIndex] = $val;
            $this->writeIndex++;
        }
    }

    public function writeInt32(int $num)
    {
        $str = dechex($num);
        if (strlen($str) < 8) {
            $str = str_pad($str, 8, 0, STR_PAD_LEFT);
        }
        foreach (str_split($str, 2) as $val) {
            $this->buf[] = $val;
            $this->writeIndex++;
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
            $this->writeIndex++;
        }
    }

    public function readInt32(): int
    {
        $byteArr = [];
        for ($i = 0; $i < 4; $i++) {
            $byteArr[] = $this->buf[$this->readIndex];
            $this->readIndex++;
        }
        $str = implode('', $byteArr);
        return hexdec($str);
    }

    public function readInt64(): int
    {
        $byteArr = [];
        for ($i = 0; $i < 8; $i++) {
            $byteArr[] = $this->buf[$this->readIndex];
            $this->readIndex++;
        }
        $str = implode('', $byteArr);
        return hexdec($str);
    }

    public function toString(): bool|string
    {
        $byteArr = array_slice($this->buf,0,$this->writeIndex);
        $str = implode('', $byteArr);
        return hex2bin($str);
    }
}