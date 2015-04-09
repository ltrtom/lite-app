<?php

namespace vendor\Core\App\Http\Util;


class Sanitizer {

    public static function sanitize($val) {
        return htmlspecialchars($val);
    }
}
