<?php

namespace LiteApplication\Service;


class ParameterContainer {

    private $parameters = [];

    function __construct($AbsoluteRootDir) {

        $this->set('root_dir', $AbsoluteRootDir);
        $this->set('src_root_dir', $AbsoluteRootDir .'/src');
    }

    public function get($name, $default=null) {

        return array_key_exists($name, $this->parameters)
            ? $this->parameters[$name]
            : $default;
    }

    public function set($name, $value) {

        if (!is_string($name)) throw new \InvalidArgumentException(sprintf("Name of parameters must be string, got %s", gettype($name)));

        $this->parameters[$name] = $value;
    }

    public function has($name) {
        return array_key_exists($name, $this->parameters);
    }

}