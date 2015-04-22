<?php

namespace LiteApplication\Tests\Service;


use InvalidArgumentException;
use LiteApplication\Service\ParameterContainer;

class ParameterContainerTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var ParameterContainer
     */
    private $container;

    public function testGet() {
        $this->container->set('foo', 'bonjour');
        $this->assertEquals('bonjour', $this->container->get('foo'));
        $this->assertEmpty($this->container->get('bar'));

        $o = new \stdClass();
        $this->container->set('foo', $o);
        $this->assertEquals($o, $this->container->get('foo'));


        $this->container->set('foo', ['allo']);
        $this->assertEquals(['allo'], $this->container->get('foo'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNonStringKey() {
        $this->container->set(67, 'bonjour');
    }

    protected function setUp() {
        $this->container = new ParameterContainer(__DIR__);
    }


}