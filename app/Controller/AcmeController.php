<?php

namespace app\Controller;

use app\Service\AcmeService;

class AcmeController extends BaseController{

    /**
     * @Secured("acme.security")
     */
    public function indexAction() {

        return $this->render('index', [
            'param' => $this->getRequest()->query->get('de'),
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
            'query' => $this->getRequest()->query->all()
        ];
    }

    public function rootAction() {
        return $this->render('root');
    }

    /**
     * @Secured("acme.security")
     */
    public function dataAction() {



    }

    public function init()
    {}
}
