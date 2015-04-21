<?php

namespace LiteApplication\Http\Util;


class Sanitizer {

    public static function sanitize($val) {

        if (is_string($val)) return htmlspecialchars($val);

        if (is_array($val)) {
            return array_map(function($item){
                return static::sanitize($item);
            }, $val);
        }

        return null;
    }
}
