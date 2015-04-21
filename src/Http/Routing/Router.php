<?php

namespace LiteApplication\Http\Routing;

use LiteApplication\Http\Util\Sanitizer;
use LiteApplication\Service\Service;

/**
 * @Service(name="router", arguments=['%ROUTES_FILE'])
 */
class Router {

    private $routeFile;

    private $routes = array();

    private $prefix;

    function __construct($routeFile) {
        $this->routeFile = $routeFile;

        $this->read();
    }

    public function match($pathInfo, $method) {

        foreach($this->routes as $route){

            if (preg_match($route['regex'], $pathInfo, $matches) && $method === $route['method']){

                $matches = array_slice($matches, 1); // remove the full routes

                // put the params values from the regex
                $cpt = 0;
                foreach ($route['params'] as $key => &$value){
                    $value = $this->sanitize($matches[$cpt]);
                    $cpt++;
                }

                return $route;
            }
        }
        return null;
    }

    private function read() {

        if (!is_file($this->routeFile)){
            throw new RouteException("Unable to find the routes file at '%s' ", $this->routeFile);
        }

        $lines = file($this->routeFile);

        foreach ($lines as $line) {

            $line = trim($line);


            if (!$line || preg_match('/^#/', $line)){

                $this->checkSpecialSymbols($line);
                continue;
            }

            $split = preg_split('/\s+/', $line);
            if (count($split) != 3) { // should be 'GET /traders  Trader::findAll()'
                throw new RouteException("Routes file is not compliant: expect 3 values. got %s", $line);
            }

            $route = array(
                'method' => $split[0],
                'route' => ($this->prefix !== null) ? $this->prefix . $split[1] : $split[1],
                'regex' => '',
                'controller' => '',
                'action' => '',
                'params' => array()
            );

            if (false === strpos($split[2], '::')){
                throw new RouteException("Routes file is not compliant: Controller part should be Controller::action");
            }

            list($controller, $action) = explode('::', $split[2]);

            $route['controller'] = $controller;
            $route['action'] = $action;


            list($reg, $params) = $this->buildRegex($route['route']);
            $route['regex']  = $reg;
            $route['params'] = $params;

            $this->routes[] = $route;
        }

    }


    private function buildRegex($route) {

        $regex  = '#^'. str_replace('/', '\/', $route). "\/?$#";
        $params = array();

        // ex /traders
        if (false === ($pos = strpos($route, '{'))){
            return array($regex, $params);
        }

        for ($i = $pos; $i< strlen($route); $i++){

            $nextBrace = strpos($route, '}', $pos);
            if (false === $nextBrace){
                throw new RouteException("Missing closing brace '}'in routes", $route);
            }

            $param = substr($route, $pos, ($nextBrace - $pos) + 1);
            $params[trim($param, '{}')] = ''; // the values will be filled during the match()

            $regex = str_replace($param, '([\.\w_-]+)', $regex);

            $pos = strpos($route, '{', $nextBrace);

            if (false === $pos){
                break;
            }

        }
        return array($regex, $params);
    }

    public function getRoutes() {
        return $this->routes;
    }

    private function checkSpecialSymbols($line) {

        if (!$line ||!preg_match('/^#<(\w+)>(.*)/', $line, $matches)) return;

        switch ($matches[1]) {
            case 'prefix' :
                $this->prefix = trim($matches[2]);
                break;
        }
    }

    private function sanitize($val) {
        return Sanitizer::sanitize($val);
    }


    /**
     * @return string|null
     */
    public function getPrefix() {
        return $this->prefix;
    }

    public function findRouteByControllerAction($controllerAction) {

        foreach($this->routes as $route) {

            // looking for 'Acme::index'
            if ($controllerAction === sprintf("%s::%s", $route['controller'], $route['action'])) {
                return $route;
            }

        }

        return null;
    }

    public function generate($controllerAction, array $params = [], $isAbsolute = false) {

        $route = $this->findRouteByControllerAction($controllerAction);

        if (!$route) throw new RouteException("Route not found for '%s'", $controllerAction);
        $url = $route['route'];

        // something like '/acme/{id}'
        if (!empty($route['params'])) {

            $keys = array_keys($route['params']);

            foreach($keys as $key) {
                if (!isset($params[$key])) throw new RouteException("Missing key '%s' to generate route %s", $key, $controllerAction);

                $value = $params[$key];

                if (!is_string($value)) throw new \InvalidArgumentException(sprintf("Expected string value to generate url, got %s", gettype($value)));

                $url = str_replace(sprintf("{%s}", $key), $value, $url);

                // remove the route param
                unset($params[$key]);
            }
        }

        if (!empty($params)) {
            $url .= '?'.http_build_query($params);
        }


        if ($isAbsolute) {
            $url = $this->getBasePath() . $url;
        }

        return $url;

    }

    public function getBasePath() {

        return sprintf("%s://%s%s%s",
            $_SERVER['REQUEST_SCHEME'],
            $_SERVER['SERVER_NAME'],
            '80' === (string) $_SERVER['SERVER_PORT'] ? '' : ':'.$_SERVER['SERVER_PORT'],
            !empty($_SERVER['REQUEST_URI']) ? dirname($_SERVER['REQUEST_URI']) : ''
            );

    }



}

