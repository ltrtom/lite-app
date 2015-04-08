<?php

spl_autoload_register(function($class) {

    $dir = dirname(__DIR__);
    $class =  $dir .'/'. str_replace('\\', '/', $class) . '.php';

    if (!is_file($class)) {
        throw new \Exception(sprintf("unable to load %s", $class));
    }
    require $class;

});
