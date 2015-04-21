<?php

namespace LiteApplication\Http\Exception;

use LiteApplication\Http\Codes;

class UnauthorizedException extends HttpException {


    function __construct($content='')
    {
        parent::__construct(Codes::UNAUTHORIZED, Codes::unauthorized(), $content);
    }
}