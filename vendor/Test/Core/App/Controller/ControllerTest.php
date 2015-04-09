<?php

namespace vendor\Test\Core\App\Controller;

use vendor\Test\Core\App\Mock\FakeController;

class ControllerTest extends \PHPUnit_Framework_TestCase{

    /**
     * @var FakeController
     */
    private $controller;

    protected function setUp()
    {
        $this->controller = new FakeController();
    }

    /**
     * @expectedException vendor\Core\App\Http\Exception\BadRequestException
     */
    public function testBadRequest()
    {
        $this->controller->_badRequest();
    }

    /**
     * @expectedException vendor\Core\App\Http\Exception\NotFoundException
     */
    public function testNotFound()
    {
        $this->controller->_notFound();
    }


}