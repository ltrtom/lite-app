<?php

namespace vendor\Core\App\Http\Exception;


use vendor\Core\App\Http\Codes;

class ForbiddenException extends HttpException {

    function __construct($content='') {

        parent::__construct(Codes::FORBIDDEN, Codes::forbidden(), $content);
    }
}