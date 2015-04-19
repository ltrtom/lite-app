<?php

namespace app\Service;

use vendor\Core\App\Http\Request;
use vendor\Core\App\Security\SecuredInterface;
use vendor\Core\App\Service\Service;

/**
 * @Service(name="acme.security", arguments=['@acme.oauth'])
 */
class SecurityService implements SecuredInterface {

    /**
     * @var OauthService
     */
    private $oauth;

    function __construct(OauthService $oauth) {
        $this->oauth = $oauth;
    }


    public function vote(Request $request) {

        $token = $request->getBearer();

        if (!$token) return false;

        $user = $this->oauth->me($token);

        return !!$user;
    }
}