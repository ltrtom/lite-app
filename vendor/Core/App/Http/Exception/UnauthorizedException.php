<?php

namespace vendor\Core\App\Http\Exception;


use vendor\Core\App\Http\Codes;

class UnauthorizedException extends HttpException {


    function __construct($content='')
    {
        parent::__construct(Codes::UNAUTHORIZED, Codes::unauthorized(), $content);
    }
}