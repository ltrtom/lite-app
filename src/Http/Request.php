<?php

namespace LiteApplication\Http;

class Request {

    /**
     * @var ParameterBag
     */
    public $request;

    /**
     * @var ParameterBag
     */
    public $query;

    /**
     * @var ParameterBag
     */
    public $headers;

    function __construct(ParameterBag $request, ParameterBag $query, ParameterBag $headers)
    {
        $this->request = $request;
        $this->query = $query;
        $this->headers = $headers;
    }

    public static function createFromGlobals() {

        return new Request(
            new ParameterBag($_GET),
            new ParameterBag($_POST),
            new ParameterBag(
                function_exists('getallheaders')
                ? getallheaders()
                : []
            )
        );
    }

    public function getBearer() {

        if (!$this->headers->has('Authorization')) return null;

        $bearer = $this->headers->get('Authorization');

        if (0 === stripos($bearer, 'bearer ')) return substr($bearer, 7);
        else return $bearer;
    }

}