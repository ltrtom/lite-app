<?php

namespace LiteApplication\Tests;


use LiteApplication\LiteApplication;


class LiteApplicationTest extends \PHPUnit_Framework_TestCase{

    /**
     * @var LiteApplication
     */
    private $app;

    public function testLite() {
        $this->markTestSkipped();

    }

    protected function setUp() {
        $this->app = new LiteApplication();

    }
}
