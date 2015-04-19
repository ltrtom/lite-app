<?php

namespace vendor\Test\Core\Routing;

use vendor\Core\Routing\RouteException;
use vendor\Core\Routing\Router;

class RouterTest extends \PHPUnit_Framework_TestCase{

    private static $routeFile;

    private static $defaultRoutes = [
        'GET /acme        Acme::index',
        'GET /acme/{de}   Acme::show',
        '#<prefix> /v1',
        '# a simple comment'

    ];

    /**
     * @var Router
     */
    private $router;

    public static function setUpBeforeClass()
    {
        self::$routeFile = dirname(__FILE__) . '/test_routes.test';
    }

    public function testSimpleRoute() {
        $this->assertCount(2, $this->router->getRoutes());
        $route = $this->router->getRoutes()[0];

        $this->assertNotEmpty($route);
        $this->assertEquals($route['controller'], 'Acme');
        $this->assertEquals($route['action'], 'index');
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
            '/acme'       => true,
            '/deoto'      => false,
            ''            => false,
            '/acme/dede'  => true,
            '/acme/de/de' => false
        ];

        foreach($expected as $pattern => $result) {

            $match = $this->router->match($pattern, 'GET');

            if ($result) $this->assertNotEmpty($match);
            else $this->assertEmpty($match);

        }

    }

    /**
     * @expectedException vendor\Core\Routing\RouteException
     */
    public function testMissingBrace(){

        $this->bootstrap([
            'GET /acme/{dede Acme::DEDED'
        ]);

    }

    /**
     * @expectedException vendor\Core\Routing\RouteException
     */
    public function testWrongNumberElements(){

        $this->bootstrap([
            'GET /acme/dede '
        ]);
    }

    public function testGenerateUrl() {
        $url =  $this->router->generate('Acme::index');
        $this->assertEquals('/acme', $url);

        $url =  $this->router->generate('Acme::index', [], true);

        $this->assertEquals('http://localhost:8082/acme', $url);

        $url =  $this->router->generate('Acme::index', [], true);

    }

    /**
     * @expectedException vendor\Core\Routing\RouteException
     */
    public function testGenerateRouteNotFound(){

        $this->router->generate('Acme::indexToto');
    }

    /**
     * @expectedException vendor\Core\Routing\RouteException
     */
    public function testGenerateRouteMissingParam(){

        $this->router->generate('Acme::show', ['foo' => 'allo']);
    }

    public function testGenerateUrlWithRoutesParams() {

        $this->bootstrap([
            'GET /acme                  Acme::index',
            'GET /acme/{name}/foo/{id}  Acme::showFoo'
        ]);

        $params = ['name' => 'allo', 'id' => 'bonjour'];
        $expected = '/acme/allo/foo/bonjour';

        $this->assertEquals($expected, $this->router->generate('Acme::showFoo', $params));

        // add GET param
        $params['sort'] = 'name';

        $this->assertEquals(
            $expected .'?sort=name',
            $this->router->generate('Acme::showFoo', $params)
        );

        $this->assertEquals(
            '/acme?param=toto',
            $this->router->generate('Acme::index', ['param' => 'toto']));
    }



    private function bootstrap($routes=null) {

        $_SERVER['REQUEST_SCHEME'] = 'http';
        $_SERVER['SERVER_NAME'] = 'localhost';
        $_SERVER['SERVER_PORT'] = 8082;

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
