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

        /** @var AcmeService $service */
        $service = $this->get('acme.service');

        return $this->render('index', [
            'hello' => $service->getServiceFile()
        ]);
    }


    public function apiAction($param) {
        return [
            'posts' => ['dede', 'foo', 'bar'],
            'param' => $param,
            'query' => $this->query->all()
        ];
    }

    public function init()
    {

    }
}
