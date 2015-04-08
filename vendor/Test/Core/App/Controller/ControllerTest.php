<?php

namespace Core\App\Controller;

use Core\App\Controller\Fatch\FatchController;
use PHPUnit_Framework_TestCase;

class ControllerTest extends PHPUnit_Framework_TestCase{

    private $controller;

    public function testGetParam()
    {
        $this->assertInstanceOf('Controller', $this->controller);
    }


    protected function setUp()
    {
        $this->controller = new FatchController();
    }


}