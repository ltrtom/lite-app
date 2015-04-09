<?php

namespace vendor\Core\App\Http\Exception;


use vendor\Core\App\Http\Codes;

class NotFoundException extends HttpException {


    function __construct($content='') {
        parent::__construct(Codes::NOT_FOUND, Codes::notFound(), $content);

    }
}
