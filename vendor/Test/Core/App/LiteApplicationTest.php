<?php

namespace Core\App;


use vendor\Core\App\LiteApplication;

define('SERVICES_FILE', __DIR__.'/Mock/');

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