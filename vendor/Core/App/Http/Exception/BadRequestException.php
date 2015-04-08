<?php

namespace vendor\Core\App\Http\Exception;


use vendor\Core\App\Http\Codes;

class BadRequestException extends HttpException{

    function __construct($content='') {

        parent::__construct(Codes::BAD_REQUEST, Codes::badRequest(), $content);
    }


}