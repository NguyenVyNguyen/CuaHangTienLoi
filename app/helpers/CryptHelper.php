<?php

class CryptHelper
{
    public static function hashMD5(string $input): string
    {
        return md5($input);
    }
}