<?php

spl_autoload_register(function($class) {

    $class = str_replace('\\', '/', $class) . '.php';


    if (is_file($class)) {
        require $class;
        return true;
    }


    return false;
});
