<?php

namespace LiteApplication\Http\Exception;



use LiteApplication\Http\Codes;

class ForbiddenException extends HttpException {

    function __construct($content='') {

        parent::__construct(Codes::FORBIDDEN, Codes::forbidden(), $content);
    }
}
