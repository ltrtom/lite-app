<?php

namespace app\Controller;


use app\Service\AcmeService;

class AcmeController extends BaseController{

    private $acme;

    public function init() {
        $this->acme = ['foo'];
    }

    public function indexAction() {

        return $this->render('index', [
            'param' => $this->query->get('de')
        ]);
    }

    public function showAction($id) {

        /** @var AcmeService $service */
        $service = $this->get('acme.service');

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
}