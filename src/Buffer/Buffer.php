<?php

namespace Sniper\Buffer;

class Buffer
{
    public string $buf = '';

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
        $this->buf .= $str;
    }


    public function writeInt(int $num)
    {
        $this->writeInt32($num);
    }

    public function writeInt16(int $num)
    {
        $hex = 0X00;
        $this->buf .= chr($num >> 8 | $hex) . chr($num | $hex);
    }

    public function writeInt32(int $num)
    {
        $hex = 0X00;
        $this->buf .= chr($num >> 24 | $hex) . chr($num >> 16 | $hex) . chr($num >> 8 | $hex) . chr($num | $hex);
    }

    public function writeInt64(int $num)
    {
        $hex = 0X00;
        $this->buf .= chr($num >> 56 | $hex).chr($num >> 48 | $hex).chr($num >> 40 | $hex).chr($num >> 32 | $hex).chr($num >> 24 | $hex) . chr($num >> 16 | $hex) . chr($num >> 8 | $hex) . chr($num | $hex);
    }

    public function readInt32(): int
    {

    }

    public function readInt64(): int
    {

    }

    public function toString(): bool|string
    {
        return $this->buf;
    }
}

