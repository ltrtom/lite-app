<?php

namespace app\Controller;


use vendor\Core\App\Controller\Controller;

/**
 * @Secured("acme.security")
 */
class FeedController extends Controller{

    public function init() {}

    public function allAction() {
        return ['all'];
    }

    public function oneAction($id) {
        return [
            'id' => $id
        ];
    }
}