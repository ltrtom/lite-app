<?php

namespace vendor\Core\View;


use vendor\Core\Routing\Router;

class ViewBag {

    /**
     * @var Router
     */
    private $router;

    function __construct(Router $router) {
        $this->router = $router;
    }


    public function link($controllerAction, array $params = [], $isAbsolute = false) {

        return $this->router->generate($controllerAction, $params, $isAbsolute);
    }


}
