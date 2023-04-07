<?php

namespace Sniper\Buffer;

use Sniper\Exceptions\BufferLengthErrorException;

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

    public function readBytes(int $len)
    {
        if(\strlen($this->buf) < $len) {
            throw new BufferLengthErrorException();
        }
        return substr($this->buf,0,$len);
    }

    public function readInt16():int
    {
        if(\strlen($this->buf) < 2) {
            throw new BufferLengthErrorException();
        }
        $num = 0X0000;
        $num  = $num | (ord($this->buf[2]) << 8);
        $num  = $num | (ord($this->buf[3]));
        return $num;
    }

    public function readInt32(): int
    {
        if(\strlen($this->buf) < 4) {
            throw new BufferLengthErrorException();
        }
        $num = 0X00000000;
        $num  = $num | (ord($this->buf[0]) << 24);
        $num  = $num | (ord($this->buf[1]) << 16);
        $num  = $num | (ord($this->buf[2]) << 8);
        $num  = $num | (ord($this->buf[3]));
        return $num;
    }

    public function readInt64(): int
    {
        if(\strlen($this->buf) < 8) {
            throw new BufferLengthErrorException();
        }
        $num = 0X0000000000000000;
        $num  = $num | (ord($this->buf[0]) << 56);
        $num  = $num | (ord($this->buf[0]) << 48);
        $num  = $num | (ord($this->buf[0]) << 40);
        $num  = $num | (ord($this->buf[0]) << 32);
        $num  = $num | (ord($this->buf[0]) << 24);
        $num  = $num | (ord($this->buf[1]) << 16);
        $num  = $num | (ord($this->buf[2]) << 8);
        $num  = $num | (ord($this->buf[3]));
        return $num;
    }

    public function length():int
    {
        return \strlen($this->buf);        
    }

    public function toString(): bool|string
    {
        return $this->buf;
    }
}