<?php

namespace vendor\Core\App\Service;


class ServiceContainer {

    const REGEX_ANNOTATION = "/@Service\\(name=[',\"]([.\\w]+)[',\"](,\\s*arguments=\\[(.+)\\])?\\)/";

    private $servicesFile;


    private $services = [];

    /**
     * @var Service[]
     */
    private $registered = [];

    function __construct($servicesFile)
    {
        $this->servicesFile = $servicesFile;
    }


    /**
     * @param $name string name
     * @return mixed
     * @throws ServiceException
     */
    public function get($name) {

        if (isset($this->services[$name])) return $this->services[$name];

        if (!isset($this->registered[$name])) {
            throw new ServiceException("No service found for %s", $name);
        }

        $class = $this->registered[$name]->getClass();
        return $this->services[$name] = new $class;
    }

    public function registerServices($internalServices=[]) {

        if (!is_file(SERVICES_FILE)) return;
        $services = [];

        require SERVICES_FILE;

        if (!get_defined_vars()['services']) throw new ServiceException('Services variables must be available in %s', SERVICES_FILE);

        if (!is_array($services)) throw new ServiceException('services variables should be array, got %s', gettype($services));


        $services = array_merge($services, $internalServices);

        foreach($services as $clazz){
            $this->register($clazz);
        }
    }

    private function createService($content, $class) {

        $start = strpos($content, '@Service');
        if (false === $start) throw new ServiceException('@Service should be present as Annotation class');

        // looking for the end of comment
        $end =  strpos($content, '*/');

        if (false === $end) throw new ServiceException('Malformed in %s', $class);
        $annotation = substr($content, $start, $end);

        if (false === preg_match(self::REGEX_ANNOTATION, $annotation, $matches)) {
            throw new ServiceException('Annotation malformed on %s, got %s', $class, $annotation);
        }

        $name = $matches[1];
        $arguments = isset($matches[3]) ?: '';
        $dependencies = [];

        if ($arguments) {
            $dependencies = array_map(function($item) {
                return trim($item, "\"'");

            }, explode(',', $arguments));
        }

        return new Service($name, $class, $dependencies);
    }

    /**
     * @return Service[]
     */
    public function getRegistered()
    {
        return $this->registered;
    }

    public function register($clazz) {

        $class = str_replace('\\', '/', $clazz);

        $file = ROOT_DIR.'/'.$class .'.php';
        if (!is_file($file)) throw new ServiceException("Service %s not found", $class);

        $content = file_get_contents($file);

        $service = $this->createService($content, $clazz);
        $this->registered[$service->getName()] = $service;
    }


}