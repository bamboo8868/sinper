<?php
namespace Bamboo\Sniper;

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
        if (is_string($str)) {
            $len = strlen($str);
            for ($i = 0; $i < $len; $i++) {
                $this->buf[] = dechex(ord($str[$i]));
            }
        }
    }


    public function writeInt(int $num): bool|string
    {
        return $this->writeInt32($num);
    }

    public function writeInt32(int $num): bool|string
    {
        $str = dechex($num);
        $str = str_pad($str,8,0,STR_PAD_LEFT);
        return hex2bin($str);
    }

    public function writeInt64(int $num): bool|string
    {
        $str = dechex($num);
        $str = str_pad($str,16,0,STR_PAD_LEFT);
        return hex2bin($str);
    }

    public function toString(): bool|string
    {
        $str = implode('',$this->buf);
        return hex2bin($str);
    }

}