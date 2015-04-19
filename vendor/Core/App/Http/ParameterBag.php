<?php

namespace vendor\Core\App\Http;


use vendor\Core\App\Http\Util\Sanitizer;

class ParameterBag {


    private $elements = [];

    function __construct($globals=null)
    {
        if (!empty($globals)) $this->addAll($globals);
    }

    public function add($rawKey, $rawValue) {
        $k = Sanitizer::sanitize($rawKey);
        $v = Sanitizer::sanitize($rawValue);

        $this->elements[$k] = $v;
    }

    public function get($key, $default = null) {

        return $this->has($key)
            ? $this->elements[$key]
            : $default;
    }

    public function has($key) {
        return array_key_exists($key, $this->elements);
    }

    public function all() {
        return $this->elements;
    }

    public function keys() {
        return array_keys($this->elements);
    }

    public function addAll(array $global) {

        foreach($global as $k => $v) $this->add($k, $v);
    }

}
