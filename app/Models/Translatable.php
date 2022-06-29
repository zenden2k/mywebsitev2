<?php


namespace App\Models;


trait Translatable
{
    public function __get($name)
    {
        if (array_key_exists($name, $this->attributes)){
            return parent::__get($name);
        }
        $lang = substr(\App::getLocale(),0,2);
        if (!$lang) {
            $lang = 'en';
        }
        $key = $name . '_'.$lang;

        if (array_key_exists($key, $this->attributes)){
            return parent::__get($key);
        }
        return parent::__get($name);
    }

}
