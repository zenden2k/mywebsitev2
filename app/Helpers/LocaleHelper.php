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

    public static function pluralizeRussian(int $n, array $variants) {
        return $n%10==1&&$n%100!=11?$variants[0]:($n%10>=2&&$n%10<=4&&($n%100<10||$n%100>=20)?$variants[1]:$variants[2]);
    }

    public static function pluralize(string $key, int $n) {
        if (str_starts_with(\App::getLocale(), 'ru')) {
            $str = __($key);
            $variants = explode('|', $str);
            return str_replace(':count', $n, self::pluralizeRussian($n, $variants));
        } else {
            return trans_choice($key, $n);
        }
    }

    public static function timeAgo($datetime,$ago = '') {
        $interval = date_create('now')->diff($datetime);

        if ($interval->y >= 1) {
            return self::pluralize('messages.years_ago',$interval->y) .$ago;
        }
        if ($interval->m >= 1) {
            return self::pluralize('messages.months_ago',$interval->m).$ago;
        }
        if ($interval->d >= 1) {
            return self::pluralize('messages.days_ago',$interval->d).$ago;
        }
        return __("today");
        /*if ($interval->h >= 1) {return $interval->h.' часов';}
        if ($interval->i >= 1) {return $interval->i.' минут';}
        return $interval->s.' секунд';*/

    }

}
