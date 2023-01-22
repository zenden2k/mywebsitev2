<?php

namespace App\Helpers;

class LocaleHelper
{
    public static function getCurrentLanguage(): string
    {
        $lang = substr(\App::getLocale(), 0, 2);
        if (!$lang) {
            $lang = 'en';
        }
        return $lang;
    }
}
