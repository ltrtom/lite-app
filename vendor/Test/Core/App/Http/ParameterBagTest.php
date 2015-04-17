<?php

namespace vendor\Test\Core\App\Http;

use vendor\Core\App\Http\ParameterBag;

class ParameterBagTest extends \PHPUnit_Framework_TestCase{

    /**
     * @var ParameterBag
     */
    private $bag;

    public function testGetParam()
    {
        $this->assertEquals('dade', $this->bag->get('bonjour'));

        $this->assertEmpty($this->bag->get('toto'));
        $this->assertEquals('default', $this->bag->get('toto', 'default'));


    }

    public function testSanitize()
    {
        $this->assertEquals('&lt;script&gt;', $this->bag->get('xss'));
    }


    protected function setUp()
    {

        $this->bag = new ParameterBag(
            // mocking the $_GET global
            ['bonjour' => 'dade', 'xss' => '<script>']
        );
    }

}
