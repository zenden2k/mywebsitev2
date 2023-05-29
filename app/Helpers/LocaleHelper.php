<?php

namespace App\Helpers;

class LocaleHelper
{
    public static array $monthNames = [
        1 => 'Январь',
        'Февраль',
        'Март',
        'Апрель',
        'Май',
        'Июнь',
        'Июль',
        'Август',
        'Сентябрь',
        'Октябрь',
        'Ноябрь',
        'Декабрь'
    ];
    public static function getCurrentLanguage(): string
    {
        $lang = substr(\App::getLocale(), 0, 2);
        if (!$lang) {
            $lang = 'en';
        }
        return $lang;
    }

    public static function getMonthName(int $time): string
    {
        if (str_starts_with(\App::getLocale(), 'ru')) {
            return self::$monthNames[date('n', $time)] ?? '';
        }
        return date('F', $time);
    }
}
