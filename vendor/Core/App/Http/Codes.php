<?php

namespace vendor\Core\App\Http;


class Codes {

    const NOT_FOUND    = 404;
    const BAD_REQUEST  = 400;
    const UNAUTHORIZED = 401;
    const FORBIDDEN    = 403;

    const HTTP_1 = "HTTP/1.0 ";

    public static function notFound(){
        return static::HTTP_1 ."404 Not Found";
    }

    public static function badRequest(){
        return static::HTTP_1 ."400 Bad Request";
    }

    public static function forbidden(){
        return static::HTTP_1 ."403 Forbidden";
    }

    public static function unauthorized(){
        return static::HTTP_1 ."401 Unauthorized";
    }


}
