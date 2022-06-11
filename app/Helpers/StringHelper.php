<?php


namespace App\Helpers;


class StringHelper
{
    /**
     * @param $ip int
     * @return string
     */
    public static function ipToString($ip)
    {
        if ( $ip === null) {
            return '';
        }
        $long = 4294967295 - ($ip - 1);
        return long2ip(-$long);
    }
}
