<?php

namespace LiteApplication\Http\Exception;


use LiteApplication\Http\Codes;

class BadRequestException extends HttpException{

    function __construct($content='') {

        parent::__construct(Codes::BAD_REQUEST, Codes::badRequest(), $content);
    }


}
