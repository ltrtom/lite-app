<?php

namespace app\Controller;

use app\Service\AcmeService;

class AcmeController extends BaseController{


    public function indexAction() {

        return $this->render('index', [
            'param' => $this->query->get('de'),
            'id' => 'ded'
        ]);
    }

    public function showAction($id) {


        return $this->render('index', [
            'id'    => $id,
            'param' => 'ded'
        ]);
    }


    public function apiAction($param) {

        return $this->render('index', [
            'posts' => $this->getpost()
        ]);
    }

    public function init()
    {
        // TODO: Implement init() method.
    }
}
