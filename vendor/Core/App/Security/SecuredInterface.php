<?php

namespace vendor\Core\App\Security;

use vendor\Core\App\Http\Request;

interface SecuredInterface {

    public function vote(Request $request);

}