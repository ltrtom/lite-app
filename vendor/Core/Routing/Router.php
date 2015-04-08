<?php

namespace vendor\Core\Routing;


use vendor\Core\App\Http\Util\Sanitizer;


/**
 * @Service(name="router")
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
            throw new \Exception(sprintf("Unable to find the routes file at '%s' ", $this->routeFile));
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
                throw new \Exception(sprintf("Routes file is not compliant: expect 3 values. got %s", $line));
            }

            $route = array(
                'method' => $split[0],
                'routes' => ($this->prefix !== null) ? $this->prefix . $split[1] : $split[1],
                'regex' => '',
                'controller' => '',
                'action' => '',
                'params' => array()
            );

            if (false === strpos($split[2], '::')){
                throw new \Exception(sprintf("Routes file is not compliant: Controller part should be Controller::action"));
            }

            list($controller, $action) = explode('::', $split[2]);

            $route['controller'] = $controller;
            $route['action'] = $action;


            list($reg, $params) = $this->buildRegex($route['routes']);
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
                throw new \Exception(sprintf("Missing closing brace '}'in routes", $route));
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


}