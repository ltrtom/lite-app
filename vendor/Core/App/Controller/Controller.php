<?php

namespace vendor\Core\App\Controller;

use vendor\Core\App\Http\Exception\BadRequestException;
use vendor\Core\App\Http\Exception\NotFoundException;
use vendor\Core\App\Http\ParameterBag;
use vendor\Core\App\LiteApplication;
use vendor\Core\View\ViewRenderer;

abstract class Controller {

    /**
     * @var LiteApplication
     */
    private $app;

    /**
     * @var ParameterBag
     */
    protected $query;

    /**
     * @var ParameterBag
     */
    protected $request;


    protected function render($resource, $vars = []) {
        /** @var ViewRenderer $viewRendered */
        $viewRendered = $this->app->get('view.renderer');

        return $viewRendered->render($resource, $this->getController(), $vars);
    }

    private function getController() {
        $controller = basename(get_called_class());
        $controller = str_replace('Controller', '', $controller);

        return end(explode('\\', $controller));
    }

    public abstract function init();

    public final function initBase(){
        $this->sanitizeGlobals();
    }

    private function sanitizeGlobals() {

        $this->query   = new ParameterBag($_GET);
        $this->request = new ParameterBag($_POST);

    }

    public function setApp(LiteApplication $app) {
        $this->app = $app;
    }

    protected function notFound($msg = '') {
        throw new NotFoundException($msg);
    }

    protected function badRequest($msg = '') {
        throw new BadRequestException($msg);
    }

    protected function getArrayParam($key, $sep = ',', $default = []){}

    protected function isAjax(){}

    protected function getContent(){}

    protected function redirect($url) {

        if (!headers_sent()){
            header('Location:'.$url);
        }
        else throw new \Exception("Unable to redirect the headers have been already sent");
    }

    protected function get($name) {
        return $this->app->get($name);
    }


}
