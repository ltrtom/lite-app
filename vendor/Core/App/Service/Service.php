<?php

namespace vendor\Core\App\Service;


class Service {

    private $name;
    private $class;
    private $dependencies;

    function __construct($name, $class,  $dependencies=[]) {
        $this->name = $name;
        $this->class = $class;
        $this->dependencies = $dependencies;
    }

    public static function fromAnnotation($annotation) {
       return new Service('name', '/vareded');
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



}