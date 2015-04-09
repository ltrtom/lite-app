<?php

namespace app\Service;
use vendor\Core\Routing\Router;

/**
 * @Service(name="acme.service", arguments=['@router', '%SERVICES_FILE'])
 */
class AcmeService {

    /**
     * @var Router
     */
    private $router;


    /**
     * @var string
     */
    private $serviceFile;

    function __construct(Router $router, $serviceFile)
    {
        $this->router = $router;
        $this->serviceFile = $serviceFile;
    }

    /**
     * @return string
     */
    public function getServiceFile()
    {
        return $this->serviceFile;
    }


    public function sayHello() {

        return 'Hello';

    }


}
