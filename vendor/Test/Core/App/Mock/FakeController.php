<?php

namespace vendor\Test\Core\App\Mock;

use vendor\Core\App\Controller\Controller;

class FakeController extends Controller {

    public function init() {
        $this->doSomething();
    }

    private function doSomething() {
        $d = 'foo';
    }

    public function _notFound($msg = '')
    {
        parent::notFound($msg);
    }

    public function _badRequest($msg = '')
    {
        parent::badRequest($msg);
    }

}