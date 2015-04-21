<?php

namespace LiteApplication\Controller;



use LiteApplication\Http\Exception\BadRequestException;
use LiteApplication\Http\Exception\NotFoundException;
use LiteApplication\Http\Request;
use LiteApplication\LiteApplication;
use LiteApplication\View\ViewRenderer;

abstract class Controller {

    /**
     * @var LiteApplication
     */
    private $app;

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
    }

    public function setApp(LiteApplication $app) {
        $this->app = $app;
    }

    /**
     * @param string $msg
     * @throws NotFoundException
     */
    protected function notFound($msg = '') {
        throw new NotFoundException($msg);
    }

    protected function badRequest($msg = '') {
        throw new BadRequestException($msg);
    }

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

    /**
     * @return Request
     */
    protected function getRequest() {
        return $this->get('request');
    }

    protected function getBearer() {

        $token = $this->getRequest()->getBearer();

        if (null === $token) $this->badRequest("'Authorization' has not been found the in the request headers");

        return $token;
    }

}
