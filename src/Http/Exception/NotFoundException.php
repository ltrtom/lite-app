<?php

namespace LiteApplication\Http\Exception;



use LiteApplication\Http\Codes;

class NotFoundException extends HttpException {


    function __construct($content='') {
        parent::__construct(Codes::NOT_FOUND, Codes::notFound(), $content);

    }
}
