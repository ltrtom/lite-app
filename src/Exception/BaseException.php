<?php

namespace LiteApplication\Exception;


use Exception;

class BaseException extends \Exception{

    public function __construct($message = "", $args = null) {

        // could be a sprintf like
        if (func_num_args() > 1 && !empty($message)) {
            $rest = array_slice(func_get_args(), 1);
            $message = vsprintf($message, $rest);
        }

        parent::__construct($message, 0, null);
    }


}

