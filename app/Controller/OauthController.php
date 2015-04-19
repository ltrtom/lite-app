<?php

namespace app\Controller;


use app\Service\OauthService;
use vendor\Core\App\Controller\Controller;

class OauthController extends Controller{

    /**
     * @var OauthService
     */
    private $oauth;

    public function init() {
        $this->oauth = $this->get('acme.oauth');
    }

    public function loginAction() {

        $username = $this->getRequest()->request->get('username');
        $password = $this->getRequest()->request->get('password');

        if (empty($username) || empty($password)) $this->badRequest("Missing parameters username or password");

        $user = $this->oauth->login($username, $password);

        if (!$user) $this->badRequest("User not found or credentials incorrect");

        return $user;
    }

    public function meAction() {

        $user = $this->oauth->me($this->getBearer());

        if (!$user) $this->notFound('User not found');

        return $user;

    }

}