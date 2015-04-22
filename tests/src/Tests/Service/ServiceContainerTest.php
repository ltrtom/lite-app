<?php

namespace LiteApplication\Tests\Service;

use LiteApplication\Service\ParameterContainer;
use LiteApplication\Service\ServiceContainer;

class ServiceContainerTest extends \PHPUnit_Framework_TestCase{


    /**
     * @var ServiceContainer
     */
    private $container;

    public function testContainer() {

        $this->container->register("LiteApplication\\Tests\\Mock\\FakeService");

    }

    protected function setUp() {
        $this->container = new ServiceContainer(
            new ParameterContainer(ROOT_TEST_DIR)
        );
    }

}