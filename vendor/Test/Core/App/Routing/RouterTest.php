<?php

namespace Core\App\Routing;

use vendor\Core\Routing\Router;

class RouterTest extends \PHPUnit_Framework_TestCase{

    private static $routeFile;

    private static $defaultRoutes = [
        'GET /acme        Acme::salut',
        'GET /acme/{de} Acme::Jenecrois',
        '#<prefix> /v1',
        '# a simple comment'

    ];

    /**
     * @var Router
     */
    private $router;

    public static function setUpBeforeClass()
    {
        self::$routeFile = dirname(__FILE__) . '/test_routes';
    }

    public function testSimpleRoute() {
        $this->assertCount(2, $this->router->getRoutes());
        $route = $this->router->getRoutes()[0];

        $this->assertNotEmpty($route);
        $this->assertEquals($route['controller'], 'Acme');
        $this->assertEquals($route['action'], 'salut');
        $this->assertEquals($route['method'], 'GET');
        $this->assertEquals($route['regex'], '#^\/acme\/?$#');

    }


    public function testParamRoute() {

        $complexRoute = $this->router->getRoutes()[1];
        $this->assertArrayHasKey('de', $complexRoute['params']);
    }

    public function testSpecialSymbols() {

        $prefix = $this->router->getPrefix();
        $this->assertNotEmpty($prefix);
        $this->assertEquals('/v1', $prefix);
    }

    public function testMatches()
    {
        $expected = [
            '/acme' => true,
            '/deoto' => false,
            '' => false,
            '/acme/dede' => true
        ];

        foreach($expected as $pattern => $result) {

            $match = $this->router->match($pattern, 'GET');

            if ($result) $this->assertNotEmpty($match);
            else $this->assertEmpty($match);

        }

    }

    /**
     * @expectedException \Exception
     */
    public function testMissingBrace(){
        $this->bootstrap([
            'GET /acme/{dede Acme::DEDED'
        ]);
    }

    /**
     * @expectedException \Exception
     */
    public function testWrongNumberElements(){

        $this->bootstrap([
            'GET /acme/dede '
        ]);
    }


    private function bootstrap($routes=null) {
        $this->writeToFile($routes);
        $this->router = new Router(self::$routeFile);
    }

    protected function setUp() {
        $this->bootstrap();
    }

    protected function writeToFile($routes=null) {
        if (empty($routes)) $routes = self::$defaultRoutes;
        file_put_contents(self::$routeFile, implode("\n", $routes));
    }



}