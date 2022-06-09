<?php


namespace App\Helpers;

class Arrays
{
    public static function keysCreated($new, $old)
    {
        return array_keys(array_diff_key($new, $old));
    }

    public static function keysDeleted($new, $old)
    {
        return array_keys(array_diff_key($old, $new));
    }

    public static function keysUpdated($new, $old)
    {
        return array_diff(
            array_keys(array_diff_assoc($new, $old)),
            static::keysCreated($new, $old)
        );
    }
}
