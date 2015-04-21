<?php

namespace LiteApplication;


use LiteApplication\Controller\Controller;
use LiteApplication\Format\FormatterInterface;
use LiteApplication\Format\JsonFormatter;
use LiteApplication\Format\XmlFormatter;
use LiteApplication\Http\Codes;
use LiteApplication\Http\Exception\HttpException;
use LiteApplication\Http\Exception\UnauthorizedException;
use LiteApplication\Http\Request;
use LiteApplication\Http\Routing\Router;
use LiteApplication\Http\Util\Sanitizer;
use LiteApplication\Security\Secured;
use LiteApplication\Security\SecurityException;
use LiteApplication\Service\ServiceContainer;
use LiteApplication\Service\ServiceException;

class LiteApplication {

    const CTRL_CLASS = '\\vendor\\Core\\App\\Controller\\Controller';

    const ANNOT_SECURED = '#@Secured\("([^"]+)"\)#';

    /**
     * @var ServiceContainer
     */
    private $serviceContainer;


    private $formatters = array();


    function __construct() {
        $this->serviceContainer = new ServiceContainer(SERVICES_FILE);

    }

    public function run(){

        $this->serviceContainer->registerRequest(Request::createFromGlobals());

        $this->initFormatters();

        $this->registerServices();

        $this->runRouter();
    }

    /**
     * check that the path info (e.g. /traders) matches a routes
     * then call the targeted controller / action
     */
    private function runRouter() {

        /** @var Router $router */
        $router = $this->get('router');

        $pathInfo = Sanitizer::sanitize(
            isset($_SERVER['PATH_INFO'])
                ? $_SERVER['PATH_INFO']
                : ''
        );
        $method = strtoupper($_SERVER['REQUEST_METHOD']);

        $route = $router->match($pathInfo, $method);

        if (null === $route) {
            $this->noRouteFound($pathInfo, $method);
        } else {
            $this->callAction($route);
        }
    }


    private function noRouteFound($pathInfo, $method){
        header(Codes::notFound());
        $this->renderRoutes($pathInfo, $method);
    }

    /**
     * call the action from the controller class defined in the routes file
     *
     * @param array $route
     * @throws \Exception
     */
    private function callAction($route) {

        $class = sprintf("\\app\\Controller\\%sController", $route['controller']);

        /** @var Controller $controller */
        $controller = new $class;
        $action = $route['action'] . 'Action';

        try {

            $this->checkClassAnnotations($class);

            if (!method_exists($controller, $action)) {
                throw new \Exception(sprintf("Method %s not found in %s", $action, $route['controller']));
            }

            if (!is_subclass_of($controller, self::CTRL_CLASS)) {
                throw new \Exception(sprintf("Class %s should extend of Controller class", $class));
            }

            $this->checkMethodAnnotations($class, $action);

            $controller->setApp($this);
            $controller->initBase();
            $controller->init();

            // finally call the action from Controller class
            $response = call_user_func_array([
                $controller,
                $action
            ],
                array_values($route['params'])
            );
            $this->handleResponse($response, $route);

        } catch (HttpException $exc) {

            $this->sendHeader($exc->getHeader());

            echo $exc->getContent();
        }
    }

    private function handleResponse(&$response, $route) {
        if (empty ($response)){
            throw new \Exception('Response given is empty, forget to return some data ?');
        }

        // if is html given, we display it
        if (is_string($response)) {
            echo $response;
        }
        else {

            // Json / xml / ...
            $formatter = $this->findFormatter();

            if (null === $formatter){
                echo $response;
            }
            else{
                $this->sendHeader('Content-Type:'. $formatter->getContentType());
                echo $formatter->format($response, $route);
            }
        }
    }

    /**
     *
     * @return FormatterInterface
     */
    private function findFormatter(){

        // try content type
        $type = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : null;

        // try get parameter (helper)
        if (empty ($type)){
            $type = isset($_GET['contenttype']) ? $_GET['contenttype'] : null;
        }

        if (empty ($type)){ // we need to find the default formatter
            return $this->findDefaultFormatter();
        }

        if (array_key_exists($type, $this->formatters)){
            return $this->formatters[$type];
        }
        else{
            return $this->findDefaultFormatter();
        }
    }

    private function findDefaultFormatter() {

        /** @var FormatterInterface $formatter */
        foreach ($this->formatters as $formatter) {
            if ($formatter->isDefault()) {
                return $formatter;
            }
        }
        throw new \Exception('Provide a default formatter if the request Content-Type is not set');
    }

    public function registerFormatter($contentType, FormatterInterface $formatter){
        $this->formatters[$contentType] = $formatter;
    }

    private function renderRoutes($pathInfo, $method){

        echo $this->get('view.renderer')->render('/vendor/Core/View/route_not_found.php', null, [
            'pathInfo' => $pathInfo,
            'method'   => $method,
            'routes'   => $this->get('router')->getRoutes()
        ]);
    }

    private function sendHeader($value) {

        if (!headers_sent()) {
            header($value);
        }
    }

    /**
     * @param $name
     * @return mixed
     * @throws \Exception
     */
    public function get($name) {

        return $this->serviceContainer->get($name);
    }

    private function initFormatters() {

        if (!empty($this->formatters)) return;

        $this->registerFormatter('application/json', new JsonFormatter());
        $this->registerFormatter('application/xml', new XmlFormatter());
    }

    private function registerServices() {

        $this->serviceContainer->registerServices([
            "vendor\\Core\\View\\ViewRenderer",
            "vendor\\Core\\Routing\\Router",
            "vendor\\Core\\App\\Session\\Session"
        ]);
    }

    private function checkClassAnnotations($class) {

        $reflection = new \ReflectionClass($class);

        $doc = $reflection->getDocComment();
        $matches = [];

        if (preg_match(static::ANNOT_SECURED, $doc, $matches)) $this->voteSecurity($matches[1]);

    }

    private function checkMethodAnnotations($class, $action) {

        $reflection = new \ReflectionMethod($class, $action);
        $doc = $reflection->getDocComment();
        $matches = [];

        if (preg_match(static::ANNOT_SECURED, $doc, $matches)) $this->voteSecurity($matches[1]);
    }


    /**
     * @param $serviceName
     * @throws SecurityException
     * @throws UnauthorizedException
     */
    private function voteSecurity($serviceName) {
        $service = null;
        try {
            $service = $this->serviceContainer->get($serviceName);
        } catch (ServiceException $exc) {
            throw new SecurityException("Unable to find the security service named '%s'", $serviceName);
        }

        if (!$service instanceof Secured) {
            throw new SecurityException("Class %s should implement the SecuredInterface to be used", $service->getClass());
        }

        $vote = $service->vote($this->get('request'));

        if (!is_bool($vote)) {
            throw new SecurityException("The 'vote' method should return a boolean result, got %s", gettype($vote));
        }

        if (false === $vote) throw new UnauthorizedException('Access Denied');
    }
}
