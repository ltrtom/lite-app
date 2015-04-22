<?php

namespace LiteApplication\Security;

use LiteApplication\Http\Exception\UnauthorizedException;
use LiteApplication\Service\ServiceContainer;
use LiteApplication\Service\ServiceException;

class SecurityHandler {

    const ANNOTATION_SECURED = '#@Secured\("([^"]+)"\)#';

    /**
     * @var ServiceContainer
     */
    private $container;

    function __construct(ServiceContainer $container) {
        $this->container = $container;
    }

    public function handleVote($serviceName) {

        $service = null;
        try {
            $service = $this->container->get($serviceName);
        } catch (ServiceException $exc) {
            throw new SecurityException("Unable to find the security service named '%s'", $serviceName);
        }

        if (!$service instanceof Secured) {
            throw new SecurityException("Class %s should implement the SecuredInterface to be used", $service->getClass());
        }

        $vote = $service->vote($this->container->get('request'));

        if (!is_bool($vote)) {
            throw new SecurityException("The 'vote' method should return a boolean result, got %s", gettype($vote));
        }

        if (false === $vote) throw new UnauthorizedException('Access Denied');

    }

}