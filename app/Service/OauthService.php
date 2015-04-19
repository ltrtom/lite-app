<?php

namespace app\Service;

use vendor\Core\App\Service\Service;

/**
 * @Service(name="acme.oauth", arguments=['@acme.dao'])
 */
class OauthService {

    /**
     * @var Dao
     */
    private $dao;


    private $defaultProjection = ['password' => 0];


    function __construct(Dao $dao) {
        $this->dao = $dao;
    }


    public function me($token) {

        return $this->dao->getUsers()->findOne([
            'token' => $token
        ],
            $this->defaultProjection
        );
    }

    public function login($username, $password) {

        $user = $this->dao->getUsers()->findOne([
            'username' => $username,
            'password' => $this->hash($password)
        ],
            $this->defaultProjection
        );

        if (!$user) return null;

        $user['token'] = $this->generateToken();
        $this->dao->getUsers()->update(['username' => $username], $user);

        return $user;
    }

    private function hash($password) {

        return hash('sha512', $password);
    }

    private function generateToken() {
        return $this->hash(uniqid(time()));
    }



}