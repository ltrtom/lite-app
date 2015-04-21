<?php

namespace LiteApplication\View;

use LiteApplication\Http\Routing\Router;

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
