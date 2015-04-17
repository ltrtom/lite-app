<?php

namespace vendor\Test\Core\App\Mock;

use vendor\Core\App\Controller\Controller;

class FakeController extends Controller {

    public function init() {
        $this->doSomething();
    }

    private function doSomething() {

        return [
            'foo' => 'bar'
        ];

    }

    public function getNotFound($msg = '')
    {
        parent::notFound($msg);
    }

    public function getBadRequest($msg = '')
    {
        parent::badRequest($msg);
    }

}
