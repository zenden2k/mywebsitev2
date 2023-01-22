<?php

namespace App\Models;

use App\Helpers\ArrayHelper;

trait Translatable
{
    public function __get($name)
    {
        if (array_key_exists($name, $this->attributes)) {
            return parent::__get($name);
        }
        $lang = substr(\App::getLocale(), 0, 2);
        if (!$lang) {
            $lang = 'en';
        }
        $key = $name . '_' . $lang;
        $val1 = null;
        $found = false;
        if (array_key_exists($key, $this->attributes)) {
            $val1 = parent::__get($key);
            $found = true;
        }

        $otherLang = $lang === 'ru' ? 'en' : 'ru';
        $key = $name . '_' . $otherLang;
        $val2 = null;
        if (array_key_exists($key, $this->attributes)) {
            $val2 = parent::__get($key);
            $found = true;
        }

        if ($found) {
            return ArrayHelper::coalesce($val1, $val2);
        }

        return parent::__get($name);
    }
}
