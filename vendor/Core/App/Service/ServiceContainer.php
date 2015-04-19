<?php

namespace vendor\Core\App\Service;


use vendor\Core\App\Http\Request;

class ServiceContainer {

    const REGEX_ANNOTATION = "/@Service\\(name=[',\"]([.\\w]+)[',\"](,\\s*arguments=\\[(.+)\\])?\\)/";



    private $services = [];

    /**
     * @var Service[]
     */
    private $registered = [];

    function __construct() {}


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

        return $this->instantiateService($name);

    }

    public function registerServices($internalServices=[]) {

        if (!is_file(SERVICES_FILE)) return;
        $services = require SERVICES_FILE;


        if (!$services || !is_array($services)) throw new ServiceException('services variables should be array, got %s', gettype($services));

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
        $arguments = isset($matches[3]) ? $matches[3] : null;
        $dependencies = [];

        if ($arguments) {
            $dependencies = array_map(function($item) {
                $item = trim($item);

                // removing the quotes and double quotes
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

    public function registerRequest(Request $request) {
        $this->services['request'] = $request;
    }

    private function instantiateService($name)
    {
        $service = $this->registered[$name];

        $reflect = new \ReflectionClass($service->getClass());

        if (!$service->hasDependencies()) {

            return $this->services[$name] = $reflect->newInstance();
        }

        $args = [];

        foreach($service->getDependencies() as $dependency) {

            if (0 === strpos($dependency, '%'))     $args[] = $this->findParameter($dependency);
            elseif (0 === strpos($dependency, '@')) $args[] = $this->findService($dependency, $service);
            else throw new ServiceException('What is the argument "%s" in %s ?', $dependency, $service->getClass());

        }

        return $this->services[$name] = $reflect->newInstanceArgs($args);

    }

    private function findParameter($param) {
        $param = substr($param, 1); // removing the '%'

        if (!defined($param)) {
            throw new ServiceException('%s is not defined as a PHP constant with defined() method', $param);
        }

        return constant($param);
    }

    private function findService($dependency, Service $service) {

        $dependency = substr($dependency, 1); // removing the '@'

        // look for a circular reference
        foreach($service->getDependencies() as $dep) {
            $serviceName = substr($dep, 1);

            if (isset($this->registered[$serviceName])) {
                foreach($this->registered[$serviceName]->getDependencies() as $mirrorDep) {

                    if ($mirrorDep === '@'. $service->getName()) {
                        throw new ServiceException("Circular reference between @%s and @%s", $service->getName(), $dependency);
                    }
                }
            }
        }


        return $this->get($dependency);
    }


}
