<?php

namespace Core\App\Http;


use vendor\Core\App\Http\ParameterBag;
use vendor\Core\App\Http\Request;

class RequestTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var Request
     */
    private $request;

    public function testGetBearer() {

        $cases = [
            'Bearer token',
            'bearer token',
            'token'
        ];

        foreach ($cases as $case) {
            $this->bootstrap([], [], ['Authorization' => $case]);
            $this->assertEquals('token', $this->request->getBearer());
        }
    }

    private function bootstrap(array $get =[], array $post =[], array $headers =[]) {

        $this->request = new Request(
            new ParameterBag($get),
            new ParameterBag($post),
            new ParameterBag($headers)
        );

    }

    protected function setUp() {
        $this->bootstrap();
    }

}