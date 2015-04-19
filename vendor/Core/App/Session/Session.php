<?php

namespace vendor\Core\App\Session;
use vendor\Core\App\Service\Service;

/**
 * @Service(name="session")
 */
class Session {


    function __construct(){
        session_start();
    }

    public function put($key, $val) {
        $_SESSION[$key] = $val;
    }

    public function get($key, $default=null) {

        return array_key_exists($key, $_SESSION)
            ? $_SESSION[$key]
            : $default;
    }


}