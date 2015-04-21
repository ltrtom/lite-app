<?php

namespace LiteApplication\Security;


use LiteApplication\Http\Request;

interface Secured {

    public function vote(Request $request);

}