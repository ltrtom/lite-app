<?php

namespace LiteApplication\Service;


class Service {

    private $name;
    private $class;
    private $dependencies;

    function __construct($name, $class,  $dependencies=[]) {
        $this->name = $name;
        $this->class = $class;
        $this->dependencies = $dependencies;
    }


    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getClass()
    {
        return $this->class;
    }

    public function getParameterDependencies() {

        return $this->getSymbolDependencies('%');
    }

    public function getServiceDependencies() {

        return $this->getSymbolDependencies('@');
    }


    private function getSymbolDependencies($char) {

        return  array_filter($this->dependencies, function ($param) use ($char){
            return 0 === strpos($param, $char);
        });

    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return $this->dependencies;
    }



    public function hasDependencies() {
        return !empty($this->dependencies);
    }




}
