<?php

namespace LiteApplication\Tests\Controller;

use LiteApplication\Tests\Mock\FakeController;

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
     * @expectedException LiteApplication\Http\Exception\BadRequestException
     */
    public function testBadRequest()
    {
        $this->controller->getBadRequest();
    }

    /**
     * @expectedException LiteApplication\Http\Exception\NotFoundException
     */
    public function testNotFound()
    {
        $this->controller->getNotFound();
    }


}
