<?php

namespace vendor\Core\App\Http;


class Codes {

    const NOT_FOUND = 404;
    const BAD_REQUEST = 400;

    public static function notFound(){
        return "HTTP/1.0 404 Not Found";
    }

    public static function badRequest(){
        return "HTTP/1.0 400 Bad Request";
    }

}
