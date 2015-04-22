<?php

namespace LiteApplication\Cache;


class CacheGenerator {

    private $excluded = [

    ];

    /**
     * @param $exclude string
     * @return $this
     */
    public function addExclude($exclude) {
        $this->excluded[] = $exclude;

        return $this;
    }





}
